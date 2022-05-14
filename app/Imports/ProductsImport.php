<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductsImport implements ToModel
{
    public function startRow(): int
    {
        return 2;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ','
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // ddd($row);
        return new Product([
            'key'=> $row[0],
            'product_title'=> $row[1],
            'product_description'=> $row[2],
            'style'=> $row[3],
            'sanmar_mainframe_color'=> $row[4],
            'size'=> $row[4],
            'color_name'=> $row[5],
            'piece_price'=> $row[6],
        ]);
    }
}
