<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\Stock;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use Illuminate\Support\Facades\Http;


class InvoiceController extends Controller
{
    public function invoice(Request $request) {
        $request->validate([
            'user_id' => 'required',
            'fullname' => 'required|string|min:3|max:50',
            'address' => 'required|string|max:100',
            'product_list' => 'required|array',
            'product_list.*.name' => 'required|string',
            'product_list.*.price' => 'required|numeric',
            'product_list.*.size' => 'required|in:XXS,XS,S,M,L,XL,2XL,3XL',
            'product_list.*.color' => 'required|string|max:7',
            'product_list.*.quantity' => 'required|numeric|min:1',
        ]);

        $client = new Party([
            'name'          => 'Minny Shop',
            'phone'         => '063-268-6119',
        ]);

        $customer = new Party([
            'name'          => $request->fullname,
            'address'       => $request->address,
            'custom_fields' => [
                'order number' => '0123456789',
            ],
        ]);

        $items = [];
        foreach ($request->product_list as $item) {
            $invoice = (new InvoiceItem())
                ->title($item['name'])
                ->description('Size: '.$item['size'].', '.$item['color'])
                ->pricePerUnit($item['price'])
                ->quantity($item['quantity']);
            $items[] = $invoice;
        }

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename(time())
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        $response = Http::post('http://microservice.payment.ratchaphon1412.co/api/v1/payment/invoice/', [
            'user_id' => $request->get('user_id'),
            'amount' => $invoice->total_amount,
            'pdf' => $link,
        ]);

        if ($link) {
            $product_list = $request->get('product_list');
            foreach ($product_list as $item) {
                $product = Product::find($item['id']);
                $color = $item['color'];
                $product_color = ProductColor::where('product_id', $product->id)
                    ->whereHas('color', function ($query) use ($color) {
                        $query->where('hex_color', $color);
                    })->first();
                if (!$product_color) {
                    return ['Fail' => "Don't have this color of Product in Stock!"];
                }
                $stock = Stock::where('product_color_id', $product_color->id)
                    ->where('size', $item['size'])->first();
                if (!$stock) {
                    return ['Fail' => "Don't have this Product in Stock!"];
                }
                if ($item['quantity'] > $stock->quantity) {
                    return ['Fail' => "Don't have enough of stock for Your want"];
                }
                $stock->quantity -= $item['quantity'];
                $stock->save();
            }
        }
        return $link;
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        // return $invoice->stream();
    }
}
