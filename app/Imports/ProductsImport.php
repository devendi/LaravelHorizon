<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\Redis;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ProductsImport implements ToModel, WithStartRow
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

    public function clearString($text)
    {
$regex = <<<'END'
/
    (
    (?: [\x00-\x7F]                 # single-byte sequences   0xxxxxxx
    |   [\xC0-\xDF][\x80-\xBF]      # double-byte sequences   110xxxxx 10xxxxxx
    |   [\xE0-\xEF][\x80-\xBF]{2}   # triple-byte sequences   1110xxxx 10xxxxxx * 2
    |   [\xF0-\xF7][\x80-\xBF]{3}   # quadruple-byte sequence 11110xxx 10xxxxxx * 3 
    ){1,100}                        # ...one or more times
    )
| .                                 # anything else
/x
END;
        $clearData = preg_replace($regex, '$1', $text);

        return $clearData;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
    // $app = Redis::connection();
    // $app->set('key2', 'value test s2');
    // return $app->get('key2');
        $app = Redis::connection();



        $addname = $row[0];
        // if ($addname != 'UNIQUE_KEY'){
            
            $set_key = self::clearString($row[0]);
            $set_product_title = self::clearString($row[1]);
            $set_product_description = self::clearString($row[2]);
            $set_style = self::clearString($row[3]);
            $set_sanmar_mainframe_color = self::clearString($row[28]);
            $set_size = self::clearString($row[18]);
            $set_color_name = self::clearString($row[14]);
            $set_piece_price = self::clearString($row[33]);

            if (empty($var)) {
                $set_piece_price = 0;
            }
            $app->set("key:$addname", $set_key);
            $app->set("product_title:$addname", $set_product_title);
            $app->set("product_description:$addname", $set_product_description);
            $app->set("style:$addname", $set_style);
            $app->set("sanmar_mainframe_color:$addname", $set_sanmar_mainframe_color);
            $app->set("size:$addname", $set_size);
            $app->set("color_name:$addname", $set_color_name);
            $app->set("piece_price:$addname", $set_piece_price);
            // return $app->get("piece_price:$addname");
            // ddd($row);
            
            // $Product = Product::where('key', '=', $set_key)->first();
            if (Product::where('key', '=', $set_key)->first()){
                // echo 'a';exit;
                // return Product::where('key', $set_key)
                //     ->update([
                //         'product_title'=> $set_product_title
                //     ]);

                $validatedData['product_title'] = $set_product_title;
                $validatedData['product_description'] = $set_product_description;
                $validatedData['style'] = $set_style;
                $validatedData['sanmar_mainframe_color'] = $set_sanmar_mainframe_color;
                $validatedData['size'] = $set_size;
                $validatedData['color_name'] = $set_color_name;
                $validatedData['piece_price'] = $set_piece_price;
                
                Product::where('key', $set_key)
                    ->update($validatedData);


            }else{
                return new Product([
                    'key'=> $set_key,
                    'product_title'=> $set_product_title,
                    'product_description'=> $set_product_description,
                    'style'=> $set_style,
                    'sanmar_mainframe_color'=> $set_sanmar_mainframe_color,
                    'size'=> $set_size,
                    'color_name'=> $set_color_name,
                    'piece_price'=> $set_piece_price,
                ]);
            }
            
        // }
    }
}
