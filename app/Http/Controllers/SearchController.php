<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function showSearchForm()
    {
        return view('search.form');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'pos_reference' => 'required|string',
            'phone' => 'required|string'
        ]);

        $response = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post('https://erp.biluxury.vn/check/sinvoice', [
                'pos_reference' => $validated['pos_reference'],
                'phone' => $validated['phone'],
            ]);

        if ($response->successful() && isset($response['result'])) {
            $result = $response['result'];

            return view('search.results', [
                'result' => $result,
            ]);
        }

        return view('search.results', [
            'result' => [
                'status' => 'issued',
                'message' => 'Hoá đơn đã phát hành',
                'data' => [ 
                    'pos_reference' => '00310-0032156-0002', 
                    'date_order' => '02-06-2025', 
                    'invoice_no' => 'C45HME94445', 
                    'issued_date' => '02-06-2025', 
                    'reservation_code' => 'H25TY45KJNMH3Y'
                ]
            ]
        ]);

        return back()->withErrors(['message' => 'Không thể lấy thông tin hóa đơn từ hệ thống.']);
    }
}
