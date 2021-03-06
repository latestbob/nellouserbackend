<?php

namespace App\Http\Controllers;

use App\Models\MedicalHistory;
use App\Models\Appointment;
use App\Models\Encounter;
use App\Models\HealthCenter;
use App\Models\HealthTip;
use App\Models\Investigation;
use App\Models\Medication;
use App\Models\PaystackPayment;
use App\Models\Procedure;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Http\Request;

class ImportController extends Controller
{

    public function importEncounter(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            Encounter::create($data);
        } elseif ($request->isMethod('put')) {
            $encounter = Encounter::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $encounter->update($data);
        } elseif ($request->isMethod('delete')) {
            $encounter = Encounter::where('uuid', $request->uuid)->first();
            $encounter->delete();
        }
    }

    public function importHealthCenter(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            HealthCenter::create($data);
        } elseif ($request->isMethod('put')) {
            $center = HealthCenter::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $center->update($data);
        } elseif ($request->isMethod('delete')) {
            $center = HealthCenter::where('uuid', $request->uuid)->first();
            $center->delete();
        }
    }

    public function importHealthTip(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            HealthTip::create($data);
        } elseif ($request->isMethod('put')) {
            $center = HealthTip::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $center->update($data);
        } elseif ($request->isMethod('delete')) {
            $center = HealthTip::where('uuid', $request->uuid)->first();
            $center->delete();
        }
    }

    public function importInvestigation(Request $request)
    {
        if ($request->isMethod('post')) {
            Investigation::create($request->all());
        } elseif ($request->isMethod('put')) {
            $object = Investigation::where('uuid', $request->uuid)->first();
            if ($object) $object->update($request->all());
        } elseif ($request->isMethod('delete')) {
            $object = Investigation::where('user_uuid', $request->user_uuid);
            if ($object) $object->delete();
        }
        return ['success'];
    }

    public function importInvoice(Request $request)
    { }

    public function importMedication(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            Medication::create($data);
        } elseif ($request->isMethod('put')) {
            $object = Medication::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $object->update($data);
        } elseif ($request->isMethod('delete')) {
            $object = Medication::where('uuid', $request->uuid)->first();
            $object->delete();
        }
    }

    public function importPayment(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            PaystackPayment::create($data);
        } elseif ($request->isMethod('put')) {
            $object = PaystackPayment::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $object->update($data);
        } elseif ($request->isMethod('delete')) {
            $object = PaystackPayment::where('uuid', $request->uuid)->first();
            $object->delete();
        }
    }

    public function importProcedure(Request $request)
    {
        if ($request->isMethod('post')) {
            return Procedure::create($request->all());
        } elseif ($request->isMethod('put')) {
            $object = Procedure::where('uuid', $request->uuid)->first();
            if ($object && $object->update($request->all())) {
                return $object;
            }
        } elseif ($request->isMethod('delete')) {
            $object = Procedure::where('user_uuid', $request->user_uuid);
            if ($object->delete()) return ['successful'];
        }
        return ['nada'];
    }

    public function importMedicalHistory(Request $request)
    {
        if ($request->isMethod('post')) {
            return MedicalHistory::create($request->all());
        } elseif ($request->isMethod('put')) {
            $object = MedicalHistory::where('uuid', $request->uuid)->first();
            if ($object && $object->update($request->all())) {
                return $object;
            }
        } elseif ($request->isMethod('delete')) {
            $object = MedicalHistory::where('user_uuid', $request->user_uuid);
            if ($object->delete()) return ['successful'];
        }
        return ['nada'];
    }

    public function importVital(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            Vital::create($data);
        } elseif ($request->isMethod('put')) {
            $object = Vital::where('uuid', $request->uuid)->first();
            $data = $request->all();
            $object->update($data);
        } elseif ($request->isMethod('delete')) {
            $object = Vital::where('uuid', $request->uuid)->first();
            $object->delete();
        }
    }

    public function importUser(Request $request)
    {
        if ($request->isMethod('post')) {
            return User::create($request->all());
        } elseif ($request->isMethod('put')) {
            $object = User::where('uuid', $request->uuid)->first();
            if ($object && $object->update($request->all())) {
                return $object;
            }
        } elseif ($request->isMethod('delete')) {
            $object = User::where('uuid', $request->uuid)->first();
            if ($object->delete()) return ['successful'];
        }
        return ['nada'];
    }

    public function importAppointment(Request $request)
    {
        if ($request->isMethod('post')) {
            return Appointment::create($request->all());
        } elseif ($request->isMethod('put')) {
            $object = Appointment::where('uuid', $request->uuid)->first();
            if ($object && $object->update($request->all())) {
                return $object;
            }
        } elseif ($request->isMethod('delete')) {
            $object = Appointment::where('uuid', $request->uuid)->first();
            if ($object->delete()) return ['successful'];
        }
        return ['nada'];
    }
}
