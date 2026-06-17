<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */


    public function collection()
    {
        return Product::all();

    }

    public function headings(): array
    {
        return [
            'Sr.no',
            'Slug',
            'Product Shipping',
            'Category',
            'Vendor Id',
            'Product SKU',
            'Product Title',
            'Product GST',
            'Product HSN',
            'Product Header Image',
            'Product Image',
            'Product Description',
            'Product Specification',
            'Product Addition Information',
            'Featured Product',
            'New Arrivals',
            'Is Active',
            'In Stock',
            'Meta Title',
            'Meta Keyword',
            'Meta Description',
            'Token',
            'Updated Time',
            'Created Time',
            ];
    }
}
