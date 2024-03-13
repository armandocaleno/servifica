<?php

namespace App\Http\Livewire\Reporting;

use App\Models\Accounting;
use App\Models\AccountingConfig;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Balance extends Component
{
    public $level_global = 5;
    public $from, $to;
    public $level = 5;

    public function render()
    {
        $activo = $this->activo($this->from, $this->to);
        $pasivo = $this->pasivo($this->from, $this->to);
        $patrimonio = $this->patrimonio($this->from, $this->to);
        $ingreso = $this->gasto($this->from, $this->to);
        
        return view('livewire.reporting.balance', compact('activo', 'pasivo', 'patrimonio'));
    }

    // CALCULA EL ACTIVO
    function activo($from, $to)
    {

        $cuentas_activo = Accounting::where('account_class_id', '1')
            ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
            ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.group, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
            ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level', 'accountings.group', 'accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
            ->where('accountings.company_id', '=', session('company')->id)
            ->where('journals.state', '=', 1)
            ->whereBetween('journals.date', [$from, $to])
            ->where('accountings.account_class_id', '=', '1')
            ->orderBy('accountings.code', 'asc')
            ->get();

        $acc = [];
        $activo = [];
        $acc['total'] = 0;

        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = strtolower($value->name);
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
            $acc['grupo'] = $value->group;

            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            } else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $activo[] = $acc;
            }
        }

        // CALCULA LOS TOTALES DE LAS CUENTAS DE GRUPO
        for ($i = $this->level_global - 1; $i >= 1; $i--) {
            foreach ($cuentas_activo as $value) {
                if ($value->level == $i) {
                    $acc['id'] = $value->id;
                    $acc['codigo'] = $value->code;
                    $acc['cuenta'] = $value->name;
                    $acc['padre'] = $value->parent_id;
                    $acc['nivel'] = $value->level;
                    $acc['grupo'] = $value->group;
                    $acc['total'] = 0;

                    foreach ($activo as $v) {
                        if ($v['padre'] == $value->id) {
                            $acc['total'] += $v['total'];
                        }
                    }

                    if ($acc['total'] != 0) {
                        // $act[] = $acc;
                        $activo[] = $acc;
                    }
                }
            }
        }
        
        $activo = $this->sort($activo);
        return $activo;
    }

    // OBTIENE LA UTILIDAD DEL EJERCICIO
    function utilidad($from, $to)
    {
        $ingreso = $this->ingreso($from, $to);
        $gasto = $this->gasto($from, $to);
        $utilidad = $ingreso - $gasto;
        return $utilidad;
    }

    //CALCULA EL PASIVO
    function pasivo($from, $to)
    {

        $cuentas_pasivo = Accounting::where('account_class_id', '2')
            ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
            ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.group, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
            ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level', 'accountings.group', 'accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
            ->where('accountings.company_id', '=', session('company')->id)
            ->where('journals.state', '=', 1)
            ->whereBetween('journals.date', [$from, $to])
            ->where('accountings.account_class_id', '=', '2')
            ->orderBy('accountings.code', 'asc')
            ->get();

        $acc = [];
        $pasivo = [];
        $acc['total'] = 0;

        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = strtolower($value->name);
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
            $acc['grupo'] = $value->group;

            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            } else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $pasivo[] = $acc;
            }
        }

        // CALCULA LOS TOTALES DE LAS CUENTAS DE GRUPO
        for ($i = $this->level_global - 1; $i >= 1; $i--) {
            foreach ($cuentas_pasivo as $value) {
                if ($value->level == $i) {
                    $acc['id'] = $value->id;
                    $acc['codigo'] = $value->code;
                    $acc['cuenta'] = $value->name;
                    $acc['padre'] = $value->parent_id;
                    $acc['nivel'] = $value->level;
                    $acc['grupo'] = $value->group;
                    $acc['total'] = 0;

                    foreach ($pasivo as $v) {
                        if ($v['padre'] == $value->id) {
                            $acc['total'] += $v['total'];
                        }
                    }

                    if ($acc['total'] != 0) {
                        $pasivo[] = $acc;
                    }
                }
            }
        }
        $pasivo = $this->sort($pasivo);
        return $pasivo;
    }

    // CALCULA EL PATRIMONIO
    function patrimonio($from, $to)
    {

        $cuentas_patrimonio = Accounting::where('account_class_id', '3')
            ->where('group', '1')->get();

        $accountings = DB::table('journal_details')
            ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.group, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
            ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level', 'accountings.group', 'accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
            ->where('accountings.company_id', '=', session('company')->id)
            ->where('journals.state', '=', 1)
            ->whereBetween('journals.date', [$from, $to])
            ->where('accountings.account_class_id', '=', '3')
            ->orderBy('accountings.code', 'asc')
            ->get();

        $acc = [];
        $patrimonio = [];
        $acc['total'] = 0;

        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            $acc['id'] = $value->id;
            $acc['codigo'] = $value->code;
            $acc['cuenta'] = strtolower($value->name);
            $acc['padre'] = $value->parent_id;
            $acc['nivel'] = $value->level;
            $acc['grupo'] = $value->group;

            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $acc['total'] = floatval($value->total_debe) - floatval($value->total_haber);
            } else {
                $acc['total'] = floatval($value->total_haber) - floatval($value->total_debe);
            }

            if ($acc['total'] != 0) {
                $patrimonio[] = $acc;
            }
        }

        // REGISTRA LA UTILIDAD DEL EJERCICIO
        $id_cuenta_resultado = AccountingConfig::where('name', 'resultado')->first();
        $cuenta_resultado = Accounting::find($id_cuenta_resultado->accounting_id);

        $acc['id'] = $cuenta_resultado->id;
        $acc['codigo'] = $cuenta_resultado->code;
        $acc['cuenta'] = $cuenta_resultado->name;
        $acc['padre'] = $cuenta_resultado->parent_id;
        $acc['nivel'] = $cuenta_resultado->level;
        $acc['total'] = $this->utilidad($from, $to);
        $acc['grupo'] = $cuenta_resultado->group;
        $patrimonio[] = $acc;

        // CALCULA LOS TOTALES DE LAS CUENTAS DE GRUPO
        for ($i = $this->level_global - 1; $i >= 1; $i--) {
            foreach ($cuentas_patrimonio as $value) {
                if ($value->level == $i) {
                    $acc['id'] = $value->id;
                    $acc['codigo'] = $value->code;
                    $acc['cuenta'] = $value->name;
                    $acc['padre'] = $value->parent_id;
                    $acc['nivel'] = $value->level;
                    $acc['grupo'] = $value->group;
                    $acc['total'] = 0;

                    foreach ($patrimonio as $v) {
                        if ($v['padre'] == $value->id) {
                            $acc['total'] += $v['total'];
                        }
                    }

                    if ($acc['total'] != 0) {
                        $patrimonio[] = $acc;
                    }
                }
            }
        }
        $patrimonio = $this->sort($patrimonio);
        return $patrimonio;
    }

    // CALCULA EL INGRESO
    function ingreso($from, $to)
    {
        $accountings = DB::table('journal_details')
            ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
            ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level', 'accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
            ->where('accountings.company_id', '=', session('company')->id)
            ->where('journals.state', '=', 1)
            ->whereBetween('journals.date', [$from, $to])
            ->whereIn('accountings.account_class_id', ['4', '5'])
            ->orderBy('accountings.code', 'asc')
            ->get();

        $total_ingreso = 0;

        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $total = floatval($value->total_debe) - floatval($value->total_haber);
            } else {
                $total = floatval($value->total_haber) - floatval($value->total_debe);
            }

            $total_ingreso += $total;
        }

        return $total_ingreso;
    }

    // CALCULA EL GASTO
    function gasto($from, $to)
    {
        $accountings = DB::table('journal_details')
            ->join('accountings', 'journal_details.accounting_id', '=', 'accountings.id')
            ->join('journals', 'journal_details.journal_id', '=', 'journals.id')
            ->selectRaw('accountings.id, accountings.parent_id, accountings.level, accountings.code, accountings.name, accountings.account_class_id, accountings.account_type_id, sum(debit_value) as total_debe, sum(credit_value) as total_haber')
            ->groupBy('accountings.id', 'accountings.parent_id', 'accountings.level', 'accountings.code', 'accountings.name', 'accountings.account_class_id', 'accountings.account_type_id')
            ->where('accountings.company_id', '=', session('company')->id)
            ->where('journals.state', '=', 1)
            ->whereBetween('journals.date', [$from, $to])
            ->whereIn('accountings.account_class_id', ['6', '7'])
            ->where('accountings.account_subclass_id', '=', null)
            ->orderBy('accountings.code', 'asc')
            ->get();

        $total_gasto = 0;

        // AGREGA LAS CUENTAS DE DETALLE
        foreach ($accountings as $value) {
            // OPERACION DEPENDIENDO LA NATURALEZA DE LA CUENTA (DEUDORA O ACREEDORA)
            if ($value->account_type_id == 1) {
                $total = floatval($value->total_debe) - floatval($value->total_haber);
            } else {
                $total = floatval($value->total_haber) - floatval($value->total_debe);
            }

            $total_gasto += $total;
        }

        return $total_gasto;
    }

    // ORDENA LOS ARREGLOS DE LAS CUENTAS
    function sort(array $arr) {
        $sorted_array = [];
        $sorted_array = collect($arr)->sortBy('codigo', SORT_STRING)->toArray();
    //     foreach ($arr as $a) {
    //        if ($a['nivel'] == 1) {
    //            $ac['id'] = $a['id'];
    //            $ac['codigo'] = $a['codigo'];
    //            $ac['cuenta'] = $a['cuenta'];
    //            $ac['padre'] = $a['padre'];
    //            $ac['nivel'] = $a['nivel'];
    //            $ac['total'] = $a['total'];
    //            $ac['grupo'] = $a['grupo'];
    //            $sorted_array[] = $ac;

    //            $ac = [];
    //            foreach ($arr as $b) {
    //                if ($b['nivel'] == 2 && $b['padre'] == $a['id']) {
    //                    $ac['id'] = $b['id'];
    //                    $ac['codigo'] = $b['codigo'];
    //                    $ac['cuenta'] = $b['cuenta'];
    //                    $ac['padre'] = $b['padre'];
    //                    $ac['nivel'] = $b['nivel'];
    //                    $ac['total'] = $b['total'];
    //                    $ac['grupo'] = $b['grupo'];

    //                    $sorted_array[] = $ac;
                       
    //                    $ac = [];
    //                    foreach ($arr as $c) {
    //                        if ($c['nivel'] == 3 && $c['padre'] == $b['id']) {
    //                            $ac['id'] = $c['id'];
    //                            $ac['codigo'] = $c['codigo'];
    //                            $ac['cuenta'] = $c['cuenta'];
    //                            $ac['padre'] = $c['padre'];
    //                            $ac['nivel'] = $c['nivel'];
    //                            $ac['total'] = $c['total'];
    //                            $ac['grupo'] = $c['grupo'];
    //                            $sorted_array[] = $ac;
               
    //                            foreach ($arr as $d) {
    //                                if ($d['nivel'] == 4 && $d['padre'] == $c['id']) {
    //                                    $ac['id'] = $d['id'];
    //                                    $ac['codigo'] = $d['codigo'];
    //                                    $ac['cuenta'] = $d['cuenta'];
    //                                    $ac['padre'] = $d['padre'];
    //                                    $ac['nivel'] = $d['nivel'];
    //                                    $ac['total'] = $d['total'];
    //                                    $ac['grupo'] = $d['grupo'];
               
    //                                    $sorted_array[] = $ac;
    //                                    $ac = [];
    //                                }

    //                                if ($this->level_global > 4) {
    //                                    foreach ($arr as $e) {
    //                                        if ($e['nivel'] == 5 && $e['padre'] == $d['id']) {
    //                                            $ac['id'] = $e['id'];
    //                                            $ac['codigo'] = $e['codigo'];
    //                                            $ac['cuenta'] = $e['cuenta'];
    //                                            $ac['padre'] = $e['padre'];
    //                                            $ac['nivel'] = $e['nivel'];
    //                                            $ac['total'] = $e['total'];
    //                                            $ac['grupo'] = $e['grupo'];
                       
    //                                            $sorted_array[] = $ac;
    //                                            $ac = [];
    //                                        }
    //                                    }
    //                                }
    //                            }
    //                        }
    //                    }
    //                }
    //            }
    //        }
    //    }
        // $sorted_array = $arr;
       return $sorted_array;
   }
}
