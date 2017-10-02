<?php

class DatabaseCommand extends CConsoleCommand
{
    public function actionStatisticsPartition() {
        $list = Yii::app()->db
            ->createCommand('EXPLAIN SELECT * FROM `statistic` WHERE 1')
            ->queryAll();

        $partitions = $list[0]['partitions'];

        $existPartitions = [];

        if (($partitions !== null)
            && (strpos($partitions, ',') === -1)
            && (strpos($partitions, 'p') === 0)
        ) {
            $existPartitions[] = intval(str_replace('p', '', $partitions));
        }

        if (($partitions !== null) && (strpos($partitions, ',') > -1)) {
            $explodedPartitions = explode(',', $partitions);
            foreach ($explodedPartitions as $partition) {
                $existPartitions[] = intval(str_replace('p', '', $partition));
            }
        }

        $partitionStartTimestamp = Yii::app()->params['partitionStartTimestamp'];
        $partitionStep = Yii::app()->params['partitionStep'];
        $currentPartition = $partitionStartTimestamp;
        $timestamp = time() + $partitionStep * 1; // partition for future 5 steps
        $partitionsArray = [];

        while ($currentPartition < $timestamp) {
            $partitionsArray[] = $currentPartition;
            $currentPartition += $partitionStep;
        }

        if (count($existPartitions) === 0) {
            $query = '';

            foreach ($partitionsArray as $currentPartition) {
                $query .= sprintf('PARTITION p%s VALUES LESS THAN (%u),', $currentPartition, $currentPartition);
                $existPartitions[] = $currentPartition;
            }

            $query = substr($query, 0, -1);

            $totalQuery = sprintf('ALTER TABLE `statistic` PARTITION BY RANGE (UNIX_TIMESTAMP(`dt`)) (%s);', $query);

            echo $totalQuery . PHP_EOL . PHP_EOL;

            Yii::app()->db
                ->createCommand($totalQuery)
                ->query();
        }

        $diff = array_values(array_diff($partitionsArray, $existPartitions));

        foreach ($diff as $newPartition) {
            $query = sprintf('ALTER TABLE `statistic` ADD PARTITION (PARTITION p%s VALUES LESS THAN (%u));', $newPartition, $newPartition);

            echo $query . PHP_EOL . PHP_EOL;

            Yii::app()->db
                ->createCommand($query)
                ->query();
        }

        return $list;
    }
}
