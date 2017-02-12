<?php

class TvScheduleManager extends CApplicationComponent
{
    public function updatePointTable($pointId, $post) {
        $userId = intval(Yii::app()->user->id);
        TVSchedule::model()->deleteAll(
            "`id_point` = :id_point AND `id_user` = :id_user",
            [':id_point' => $pointId, ':id_user' => $userId]
        );

        if(is_array($post)
            && isset($post['tvScheduleFrom'])
            && isset($post['tvScheduleTo'])
        ) {
            $tvScheduleFrom = $post['tvScheduleFrom'];
            $tvScheduleTo = $post['tvScheduleTo'];

            $tvScheduleTable = $this->organize($tvScheduleFrom, $tvScheduleTo);

            foreach ($tvScheduleTable as $tvScheduleAttributes) {
                $tvSchedule = new TVSchedule;
                $tvSchedule->attributes = array_merge(
                    $tvScheduleAttributes, [
                        'id_point' => intval($pointId),
                        'id_user' => intval($userId)
                    ]);
                $tvSchedule->save();
            }
        }
    }


    private function organize ($fromArray, $toArray)
    {
        $array = [];
        if (count($fromArray) > count($toArray)) {
            $array = $this->check($fromArray, $toArray, 'dt_from', 'dt_to');
        } else {
            $array = $this->check($toArray, $fromArray, 'dt_to', 'dt_from');
        }

        $array2 = [];
        for ($ii = 0; $ii < count($array); $ii++) {
            if(strtotime ($array[$ii]['dt_from']) < strtotime ($array[$ii]['dt_to'])) {
                $array2[] = $array[$ii];
            }
        }

        return $array2;
    }

    private function check ($array1, $array2, $name1, $name2)
    {
        $arr = [];
        for ($ii = 0; $ii < count($array1); $ii++) {
            if (($array1[$ii] !== '')
               && isset($array2[$ii])
               && ($array2[$ii] !== '')
             ) {
                 $arr[] = [
                     $name1 => $array1[$ii],
                     $name2 => $array2[$ii],
                 ];
             }
        }

        return $arr;
    }
}
