<?php

namespace App\Controllers\Api;

use DateTime;
use DatePeriod;
use DateInterval;
use App\Controllers\BaseController;
use App\Models\ActivityRecord as ActivityRecordModel;

class Comparation extends BaseController
{
    private $datasets = [];
    private $groupingByTime = [
        [
            'start' => '00:00:01',
            'end'   => '03:00:00',
        ],
        [
            'start' => '03:00:01',
            'end'   => '06:00:00',
        ],
        [
            'start' => '06:00:01',
            'end'   => '09:00:00',
        ],
        [
            'start' => '09:00:01',
            'end'   => '12:00:00',
        ],
        [
            'start' => '12:00:01',
            'end'   => '15:00:00',
        ],
        [
            'start' => '15:00:01',
            'end'   => '18:00:00',
        ],
        [
            'start' => '18:00:01',
            'end'   => '21:00:00',
        ],
        [
            'start' => '21:00:01',
            'end'   => '23:59:59',
        ]
    ];

    public function index()
    {
        list($startDate, $endDate) = $this->prepareParameter();
        $dates  = $this->getRangeInDay($startDate, $endDate);
        $charts = $this->setUpChartJsData($dates);
        $labels = $this->setUpChartJsLabel($dates);

        return $this->successResponse([
            'datasets'  => $charts,
            'labels'    => $labels
        ], 'success get comparation');
    }

    private function setUpChartJsLabel()
    {
        foreach($this->groupingByTime as $time) {
            $label[] = substr($time['start'], 0, 5). '-' . substr($time['end'], 0, 5);
        }

        return $label ?? [];
    }

    private function prepareParameter()
    {
        $start  = $this->request->getGet('start_date');
        $end    = $this->request->getGet('end_date');

        $startDate  = empty($start) ? date('Y-m-d') : $start;
        $endDate    = empty($end) ? date('Y-m-d') : $end;

        return [$startDate, $endDate];
    }

    private function getRangeInDay(?string $startDate, ?string $endDate)
    {
        $period = new DatePeriod(
            new DateTime($startDate),
            new DateInterval('P1D'),
            new DateTime($endDate)
        );

        $dates = [];
        foreach ($period as $key => $value) {
            $dates[] = $value->format('Y-m-d');
        }

        $dates[] = $endDate;
        return $dates;
    }

    private function setUpChartJsData(?array &$dates)
    {
        $this->datasets = [];
        foreach($dates as $date) {
            $this->setUpTimeParameter($date);
        }

        return array_values($this->datasets);
    }

    private function setUpTimeParameter(?string $date)
    {
        $model = new ActivityRecordModel();
        foreach($this->groupingByTime as $time) {
            $start  = $date . ' ' . $time['start'];
            $end    = $date . ' ' . $time['end'];

            $total = $model->getTotalBatuk($start, $end);

            $this->datasets[$date]['label'] = (new DateTime($date))->format('d-m-Y');
            $this->datasets[$date]['data'][] = $total;
        }
    }
}
