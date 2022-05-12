<?php

namespace App\Services;
use Illuminate\Database\Eloquent\Builder as Model;

class BaseService
{
    protected function generatePagedResponse(Model $model, $perPage, $page )
    {

        if ($page) {
            $model->offset(($page - 1) * $perPage)->limit($perPage);
        }else{
            $model->limit($perPage);
        }

        return array('data' => $model->get(), 'count' => $model->count());
    }
    protected function emailToken($email){
        return md5(time().$email.rand(1,10000));
    }


}
