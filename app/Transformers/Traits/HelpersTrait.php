<?php

/**
 * Created by PhpStorm.
 * User: xuan
 * Date: 9/21/15
 * Time: 7:17 PM.
 */
namespace PHPHub\Transformers\Traits;

use McCool\LaravelAutoPresenter\HasPresenter;

trait HelpersTrait
{
    /**
     * Transform the entity.
     *
     * @param $model
     *
     * @return array
     */
    public function transform($model)
    {
        if ($model instanceof HasPresenter) {
            $model = app('autopresenter')->decorate($model);
        }

        $transformData = $this->transformData($model);

        $data = array_filter($transformData, function ($v) {
            if (is_null($v)) {
                return false;
            }

            return true;
        });

        // 转换 null 字段为空字符串
        foreach (array_keys($model->toArray()) as $key) {
            if (!is_null($transformData[$key])) {
                continue;
            }

            $data[$key] = '';
        }

        // 在 transformData 中使用 toArray 后，时间会丢失时区等信息
        if ($model->created_at) {
            $data['created_at'] = $model->created_at->format('c');
        }
        if ($model->updated_at) {
            $data['updated_at'] = $model->updated_at->format('c');
        }

        return $data;
    }
}
