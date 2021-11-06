<?php

namespace App\Http\Controllers\Api;

use App\Models\DrugRating;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCollection;
use App\Models\Cart;
use App\Models\DoctorsPrescription;
use App\Models\DrugCategory;
use App\Models\Location;
use App\Models\Order;
use App\Models\PharmacyDrug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Traits\FirebaseNotification;

class DrugController extends Controller
{
    use FirebaseNotification;

    public function index(Request $request)
    {
        $search = $request->search;
        $category = $request->categories;

        $drugs = PharmacyDrug::with('category')
            ->where('status', true)
            ->where('quantity', '>', 0)
            ->when($category, function ($query, $category) {
                $categories = explode(',', $category);
                $query->whereIn('category_id', $categories);
            })
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('brand', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            })
            ->when($request->price_min, function ($query, $price) {
                $query->where('price', '>=', $price);
            })
            ->when($request->price_max, function ($query, $price) {
                $query->where('price', '<=', $price);
            })
            ->when($request->prescription, function ($query, $pres) {
                $query->where('require_prescription', strtolower($pres) === 'yes' ? 1 : 0);
            })
            ->when($request->rating, function ($query, $rating) {
                $query->where('rating', '>=', $rating);
            })
            ->when($request->sort_by, function ($query, $sortBy) {
                switch ($sortBy) {
                    case 'price_min':
                        $query->orderBy('price');
                        break;
                    case 'price_max':
                        $query->orderBy('price', 'DESC');
                        break;
                    case 'rating':
                        $query->orderBy('rating');
                        break;
                    default:
                        $query->orderBy('name');
                        break;
                }
            })
            ->paginate(16);

        return $drugs;
    }

    public function getDrug(Request $request)
    {
        return PharmacyDrug::with('category')->where(
            [
                ['uuid', '=', $request->uuid]
                //                , ['status', '=', true]
            ]
        )->first();
    }

    public function getDrugCategories(Request $request)
    {
        return DrugCategory::where('name', '!=', "")->groupBy('name')->select(['id', 'name'])->get();
    }

    public function fetchPendingOrders(Request $request)
    {

        $user = $request->user();
        $size = empty($request->size) ? 10 : $request->size;

        $locationID = null;
        $userType = '';

        if (Auth::check() && ($userType = $user->user_type) == "agent") {
            $locationID = $user->pharmacy->location_id;
        }

        $orders = Order::whereHas('items', function ($query) use ($user) {
            // $query->where('carts.vendor_id', $user->vendor_id)
            // $query->where('carts.vendor_id', $user->vendor_id)
            //     ->where('carts.status', 'approved')
            //     ->where('carts.is_ready', 0);
        })
            ->withCount(['items'])
            ->when($locationID, function ($query, $loc) {
                $query->where('location_id', $loc);
            })
            ->where('payment_confirmed', 1)
            ->paginate($size);

        return new OrderCollection($orders);
    }

    public function drugOrders(Request $request)
    {

        $user = $request->user();
        $size = empty($request->size) ? 10 : $request->size;

        $locationID = null;
        $userType = '';

        if (Auth::check() && ($userType = $user->user_type) == "agent") {
            $locationID = $user->pharmacy->location_id;
        }

        $orders = Order::whereHas('items', function ($query) use ($user) {
            $query->where('carts.vendor_id', $user->vendor_id)
                ->where('carts.status', 'approved');
        })
            ->withCount(['items'])
            ->when($locationID, function ($query, $loc) {
                $query->where('location_id', $loc);
            })
            ->where('payment_confirmed', 1)
            ->paginate($request->limit ?? 15);

        return new OrderCollection($orders);

        // $orders = Order::query()->join('carts', 'orders.cart_uuid', '=', 'carts.cart_uuid', 'INNER');

        // $orders->when($locationID, function ($query, $locationID) {
        //     $query->where(['orders.location_id' => $locationID, 'orders.payment_confirmed' => 1]);
        // });

        // if (!empty($search = $request->search)) {

        //     $orders = $orders->whereRaw(
        //         "(orders.firstname like ? or orders.lastname like ? or
        //         orders.phone like ? or orders.email like ? or
        //         orders.company like ? or orders.city = ? or
        //         orders.order_ref = ? or orders.cart_uuid = ?)",
        //         [
        //             "%{$search}%", "%{$search}%", "%{$search}%", "%{$search}%",
        //             "%{$search}%", $search, $search, $search
        //         ]
        //     );
        // }

        // if (!empty($payment = $request->payment) && ($payment == 'paid' || $payment == 'unpaid')) {
        //     $orders = $orders->where('orders.payment_confirmed', '=', ($payment == 'paid' ? 1 : 0));
        // }

        // $dateEnd = null;

        // if (!empty($dateStart = $request->dateStart)) {

        //     $dateEnd = $request->dateEnd ?? date('Y-m-d');

        //     $orders = $orders->whereRaw(
        //         "(orders.created_at between ? and ?)",
        //         ["{$dateStart} 00:00:00", "{$dateEnd} 23:59:59"]
        //     );
        // }

        // $location = null;

        // if (empty($locationID) && !empty($location = $request->location)) {

        //     $orders = $orders->where('orders.location_id', $location);
        // }

        // $orders = $orders->where('carts.vendor_id', $request->user()->vendor_id);

        // $orders = $orders->groupBy('carts.cart_uuid')->orderByDesc('orders.id');

        // $orders = $orders->paginate($size);

        // $total = [

        //     'paid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join(
        //         'carts',
        //         'orders.cart_uuid',
        //         '=',
        //         'carts.cart_uuid',
        //         'INNER'
        //     )->where([
        //         'carts.vendor_id' => $request->user()->vendor_id,
        //         'orders.payment_confirmed' => 1
        //     ])->when($locationID, function ($query, $locationID) {
        //         $query->where('orders.location_id', $locationID);
        //     })->distinct()->count('orders.id') : null,

        //     'unpaid' => ($userType == 'admin' || $userType == 'agent') ? Order::query()->join(
        //         'carts',
        //         'orders.cart_uuid',
        //         '=',
        //         'carts.cart_uuid',
        //         'INNER'
        //     )->where([
        //         'carts.vendor_id' => $request->user()->vendor_id,
        //         'orders.payment_confirmed' => 0
        //     ])->when($locationID, function ($query, $locationID) {
        //         $query->where('orders.location_id', $locationID);
        //     })->distinct()->count('orders.id') : null

        // ];

        // $locations = $locationID ? Location::where('id', $locationID)->get() : Location::all();

        // return compact('orders', 'size', 'total', 'search', 'payment', 'dateStart', 'dateEnd', 'locations', 'location', 'userType');
    }

    public function drugOrderItems(Request $request)
    {
        if (empty($request->uuid)) {
            return response([
                'status' => false,
                'msg' => "Cart ID is missing."
            ], 400);
        }

        $user = $request->user();

        $orderItems = Cart::with(['drug:id,name,brand,price,description,image,drug_id,require_prescription'])
            ->whereHas('order', function ($query) use ($user) {
                if ($user->user_type == 'agent') {
                    $query->where('location_id', $user->pharmacy->location_id);
                }
                //$query->where('location_id', $user->location_id);
            })
            ->where([
                'cart_uuid' => $request->uuid,
                //'vendor_id' => $request->user()->vendor_id
            ]);

        if ($user->user_type == 'agent') {
            $orderItems->where('status', 'approved');
        }

        $orderItems = $orderItems->orderByDesc('id')->get();
        /*if ($user->user_type == 'agent') {
            $ids = [];
            foreach ($orderItems as $item) {
                $ids[] = $item->drug->id;
            }

            $prescriptions = DoctorsPrescription::whereIn('drug_id', $ids)
                ->where('cart_uuid', $request->uuid)
                ->get()->keyBy('drug_id');

            $orderItems = $orderItems->map(function($item) use ($prescriptions) {
                if ($item->drug->require_prescription && isset($prescriptions[$item->drug->id])) {
                    $item->presc = $prescriptions[$item->drug->id];
                }

                return $item;
            });
        }*/

        return $orderItems;

        if (empty($orderItems->first())) {
            return response([
                'status' => false,
                'msg' => "Sorry, that Cart ID either does not exist or has been deleted"
            ], 400);
        }

        if (($userType = $request->user()->user_type) == 'agent') {

            if ($orderItems->first()->order->location_id != $request->user()->pharmacy->location_id) {
                return [
                    'status' => false,
                    'msg' => "Sorry, that order is not for your pharmacy's assigned location"
                ];
            }
            $orderItems = $orderItems->where('status', 'approved');
        }

        $size = empty($request->size) ? 10 : $request->size;

        $orderItems = $orderItems->paginate($size);

        $status = true;
        $msg = "Order items retrieved successfully";

        return compact('status', 'msg', 'orderItems', 'size', 'userType');
    }


    public function drugOrderItemAction(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:carts,id',
            'status' => 'required|string|in:approved,disapproved,cancelled',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $item = Cart::where(['id' => $request->id, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($item)) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item was not found'
            ]);
        }

        $item->status = $request->status;
        $item->save();

        $isAllApproved = true;
        $items = [];

        foreach ($item->order->items as $it) {
            if ($it->status != 'approved') {
                $isAllApproved = false;
                break;
            }

            $items[] = [
                'id' => $item->id,
                'name' => $item->drug->name,
                'quantity' => $item->quantity
            ];
        }

        if ($isAllApproved) {

            $agents = [];
            foreach (($item->order->location->pharmacies ?? []) as $pharmacy) {
                foreach ($pharmacy->agents as $agent) $agents[] = $agent->device_token;
            }

            if (!empty($agents)) {

                $this->sendNotification(
                    $agents,
                    "New Order",
                    "Hello there! there's been a newly approved order for your location with Order REF: {$item->order->order_ref}",
                    'high',
                    ['orderId' => $item->order->id, 'items' => $items]
                );
            }
        }

        return response([
            'status' => true,
            'message' => "That order item has been {$request->status} successfully"
        ]);
    }

    public function drugOrderItemReady(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric|exists:carts,id',
            'is_ready' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        if ($request->is_ready != true) {
            return response([
                'status' => false,
                'message' => "Ok, order item is not ready"
            ]);
        }

        $item = Cart::where(['id' => $request->id, 'vendor_id' => $request->user()->vendor_id])->first();

        if (empty($item)) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item was not found'
            ]);
        }

        if ($item->is_ready == true) {
            return response([
                'status' => false,
                'message' => 'Sorry, that item has already been marked as ready by a pharmacy'
            ]);
        }

        $item->is_ready = $request->is_ready;
        $item->is_ready_by = $request->user()->pharmacy_id;
        $item->save();

        $isAllReady = true;

        $items = [];
        $pickup_addresses = [];

        foreach ($item->order->items as $it) {
            if ($it->is_ready != true) {
                $isAllReady = false;
                break;
            }

            $items[] = [
                'name' => $it->drug->name,
                'image' => $it->drug->image,
                'description' => $it->drug->description,
                'quantity' => $it->quantity
            ];

            $pickup_addresses[$item->is_ready_by] = [
                'name' => $item->accepted_by->name,
                'address' => $item->accepted_by->address
            ];
        }

        if ($isAllReady) {

            if ($item->order->delivery_method == 'shipping') {

                $riders = [];
                foreach ($item->order->location->riders as $rider) {
                    $riders[] = $rider->device_token;
                }

                if (!empty($riders)) {

                    $this->sendNotification(
                        $riders,
                        "New Order",
                        "Hello there! an order has been processed and is ready for pick up",
                        'high',
                        [
                            'orderId' => $item->order->id,
                            'items' => $items,
                            'customer_name' => "{$item->order->firstname} {$item->order->lastname}",
                            'customer_phone' => $item->order->phone,
                            'delivery_address' => $item->order->address1,
                            'pickup_address' => array_values($pickup_addresses)
                        ]
                    );
                } else {

                    return response([
                        'status' => false,
                        'message' => "No riders found"
                    ]);
                }
            }
        }

        return response([
            'status' => true,
            'message' => "That order has been successfully marked as ready"
        ]);
    }

    public function addPrescription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'required|exists:carts,cart_uuid',
            'id' => 'required|integer',
            'file' => 'required|file|mimes:jpeg,jpg,png,pdf',
        ]);

        if ($validator->fails()) {
            return response([
                'status' => false,
                'message' => $validator->errors()
            ]);
        }

        $item = Cart::where([
            'cart_uuid' => $request->uuid,
            'drug_id' => $request->id,
            'vendor_id' => $request->user()->vendor_id
        ])->first();

        if (empty($item)) {

            return response([
                'status' => false,
                'message' => "Failed to add prescription, item not found"
            ]);
        }

        if ($request->hasFile('file')) {

            $item->prescription = $prescription = $this->uploadFile($request, 'file');
            //            $item->prescription = $prescription = 'http://nelloadmin.com/images/drug-placeholder.png';
            $item->prescribed_by = 'vendor';
            $item->save();

            return response([
                'status' => true,
                'message' => "Prescription uploaded and added successfully",
                'prescription' => $prescription
            ]);
        } else return response([
            'status' => false,
            'message' => "No prescription file uploaded"
        ]);
    }

    public function rateDrug(Request $request)
    {
        $data = $request->validate([
            'drug_id' => 'required|exists:pharmacy_drugs,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string'
        ]);

        $data['user_id'] = Auth::user()->id;
        DrugRating::create($data);
        $count = DrugRating::where('drug_id', $request->drug_id)->count();
        $sum = DrugRating::where('drug_id', $request->drug_id)->sum('rating');
        PharmacyDrug::where('id', $request->drug_id)->update([
            'rating' => (int) ($sum / $count)
        ]);

        return ['msg' => 'Rating saved'];
    }
}
