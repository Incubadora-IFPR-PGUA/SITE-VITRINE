<?php

namespace App\Repositories;

use App\Models\ServiceOrder;
use Illuminate\Support\Facades\DB;

class ServiceOrderRepository
{
    public function getClientsXServiceOrders($startDate, $finalDate)
    {
        $serviceOrders = ServiceOrder::with('client')->select('COD_CLIENTE', DB::raw('SUM(valor_total) as total'))->groupBy('COD_CLIENTE')->where('DATA_ORDEM', '>=', $startDate)->where('DATA_ORDEM', '<=', $finalDate)->orWhereNull('DATA_ORDEM')->get();

        return $serviceOrders;
    }

    public function getAll()
    {
        return ServiceOrder::all();
    }

    public function getStartAndFinalDate()
    {
        $startDate = ServiceOrder::whereNotNull('DATA_ORDEM')->orderBy('DATA_ORDEM')->first()->DATA_ORDEM;
        $finalDate = ServiceOrder::whereNotNull('DATA_ORDEM')->orderBy('DATA_ORDEM', 'desc')->first()->DATA_ORDEM;

        return ['startDate' => $startDate, 'finalDate' => $finalDate];
    }

    public function getServiceOrderByClientId($id, $modalSelect = null, $modalitySelect = null,  $serviceTypeSelect = null)
    {
        $orders = ServiceOrder::with('status')->with('ship')->select('CODIGO', 'COD_NAVIO', 'COD_STATUS', 'NUM_OS', 'DATA_ORDEM', 'REFERENCIA', 'NUM_DI', 'NUM_LI', 'BL', 'VALOR_RECEBIDO', 'TOTAL_GASTO_DOC', 'VALOR_TOTAL', 'SALDO_RESTANTE')->where('COD_CLIENTE', $id);

        if (isset($modalSelect)) {
            $orders->where('COD_MODAL', $modalSelect);
        }

        if (isset($modalitySelect)) {
            $orders->where('COD_MODALIDADE', $modalitySelect);
        }

        if (isset($serviceTypeSelect)) {
            $orders->where('COD_TIPO_OS', $serviceTypeSelect);
        }

        return $orders->get();
    }

    public function getServiceOrdersXClient($id, $modalSelect = null, $modalitySelect = null,  $serviceTypeSelect = null)
    {
        $serviceOrders = ServiceOrder::with('status')->select('COD_STATUS', DB::raw('COUNT(*) as total'))->groupBy('COD_STATUS')->where('COD_CLIENTE', $id);

        if (isset($modalSelect)) {
            $serviceOrders->where('COD_MODAL', $modalSelect);
        }

        if (isset($modalitySelect)) {
            $serviceOrders->where('COD_MODALIDADE', $modalitySelect);
        }

        if (isset($serviceTypeSelect)) {
            $serviceOrders->where('COD_TIPO_OS', $serviceTypeSelect);
        }

        return $serviceOrders->get();
    }

    public function getServiceOrderById($id)
    {
        return ServiceOrder::with(['client', 'status', 'modal', 'modality', 'dispatch', 'ship', 'type'])->where('CODIGO', $id)->first();
    }
}
