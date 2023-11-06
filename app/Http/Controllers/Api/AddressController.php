<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

use function FlixTech\AvroSerializer\Protocol\validate;

class AddressController extends Controller
{
    public function index() {
        return Address::get();
    }
    public function store(Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'sub_district' => 'required|string',
            'zip_code' => 'required|string',
            'current_addr' => 'required|string',
        ]);

        $address = new Address();
        $address->user_id = $request->get('user_id');
        $address->address = $request->get('address');
        $address->province = $request->get('province');
        $address->district = $request->get('district');
        $address->sub_district = $request->get('sub_district');
        $address->zip_code = $request->get('zip_code');
        $address->current_addr = "0";
        $address->save();
        return $address;
    }

    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string',
            'province' => 'required|string',
            'district' => 'required|string',
            'sub_district' => 'required|string',
            'zip_code' => 'required|string',
            'current_addr' => 'required|string',
        ]);
        // changeStatus
        if($request->current_addr == "1" ){
            $C_adreses = Address::where('current_addr', '1')->get();
            foreach ($C_adreses as $C_adress) {
                $C_adress->current_addr = "0";
                $C_adress->save();
            }
        }
        $address->user_id = $request->get('user_id');
        $address->address = $request->get('address');
        $address->province = $request->get('province');
        $address->district = $request->get('district');
        $address->sub_district = $request->get('sub_district');
        $address->zip_code = $request->get('zip_code');
        $address->current_addr = $request->get('current_addr');
        $address->save();
        $address->refresh();
        return $address;
    }
        }
    
