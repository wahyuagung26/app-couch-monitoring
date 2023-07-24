<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ActivityRecord as ActivityRecordModel;

class ActivityRecord extends BaseController
{
    private $payload;

    public function store()
    {
        $params = $this->getPayload();

        $this->generatePayload($params)
             ->insert();

        return $this->successResponse([], 'success insert activity');
    }

    public function index()
    {
        $startDate  = $this->request->getGet('start_date');
        $endDate    = $this->request->getGet('end_date');

        $model      = new ActivityRecordModel();
        $activities = $model->getByPeriode($startDate, $endDate);
        $totalBatuk = $model->getTotalBatuk($startDate, $endDate);

        return $this->successResponse([
            'activities'    => $activities,
            'total_batuk'   => $totalBatuk
        ], 'success get activities');
    }

    private function generatePayload(?array $params)
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->payload = [
            'activity_name' => $params['activity'],
            'activity_note' => $params['keterangan'],
            'created_at'    => date('Y-m-d H:i:s')
        ];

        return $this;
    }

    private function insert()
    {
        if(empty($this->payload)) {
            return false;
        }

        $model = new ActivityRecordModel();
        $model->insert($this->payload);

        return $this;
    }
}
