<?php

namespace App\Transformer;

class NewsTransformer
{
     /**
     * @param array $item
     * @return array
     */
    public function runItems(array $items)
    {
        return array_map(
            function ($item) {
                return $this->runItem($item);
            }, 
            $items
        );
    }
    
    /**
     * @param array $item
     * @return array
     */
    public function runItem(array $item)
    {
        $item['ts_timestamp'] = strtotime($item['ts'] ?? 0);
        $item['ts_formated'] = date(DATE_FORMAT_SHORT, strtotime($item['ts']));
        
        if(empty($item['img'])) {
            $item['img'] = IMG_DEFAULT_300_200;
        }
        
        if(substr($item['img'], 0, 1) !== '/') {
            $item['img'] = '/' . $item['img'];
        }

        if (isset($item['description'])) {
            $item['description'] = htmlspecialchars_decode($item['description'] ?? '');
        }
        
        if (isset($item['text'])) {
            $item['text'] = htmlspecialchars_decode($item['text'] ?? '');
        }
        
        return $item;
    }
}
