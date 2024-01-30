<?php

namespace App\Http\Controllers\Reporting;

use App\Http\Controllers\Controller;
use App\Http\Livewire\Reporting\Balance;
use App\Http\Livewire\Reporting\Results;
use App\Models\Account;
use App\Models\Accounting;
use App\Models\AccountingConfig;
use App\Models\Expense;
use App\Models\JournalDetail;
use App\Models\Partner;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ReportingController extends Controller
{
    public $level_global = 5;

    public function balances()
    {
        return view('reporting.partners-balance');
    }

    public function transactions()
    {
        return view('reporting.transactions');
    }

    public function balances_excel(Request $request)
    {
        $accounts = Account::all();

        $subquery = "";
        foreach ($accounts as $account) {
            $subquery .= '(SELECT ap.value FROM account_partner ap WHERE ap.partner_id = p.id AND ap.account_id = ' . $account->id . ') as "cuenta' . $account->id . '" ,';
        }
        $subquery = trim($subquery, ',');

        $balances = DB::select('SELECT p.id, p.code, p.name, p.lastname, p.identity, ' . $subquery . ' FROM partners p WHERE p.status = 1 and (p.name LIKE "%' . $request->search . '%" or p.code LIKE "' . $request->search . '%" or p.identity LIKE "' . $request->search . '%")');

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de saldos'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'alignment' => [
                'wrapText' => true
            ]
        ];

        //formato de la fila de cabecera
        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $sheet->getStyle("A1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A1', 'SOCIO'); //título de la primera celda

        //arreglo para las celdas 
        $cells = [0 => 'B', 1 => 'C', 2 => 'D', 3 => 'E', 4 => 'F', 5 => 'G', 6 => 'H', 7 => 'I', 8 => 'J', 9 => 'K', 10 => 'L', 11 => 'M', 12 => 'N', 13 => 'O', 14 => 'P', 15 => 'Q', 16 => 'R', 17 => 'S', 18 => 'T', 19 => 'U', 20 => 'V'];

        //escribe el nombre de las cuentas en la primera columna como cabecera
        foreach ($accounts as $key => $account) {
            $sheet->getColumnDimension($cells[$key])->setWidth(18);
            $sheet->getStyle($cells[$key])->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle($cells[$key] . "1")->applyFromArray($styleArray);
            $sheet->setCellValue($cells[$key] . "1", strtoupper($account->name));
        }

        //escribe los datos de la consulta en cada celda
        foreach ($balances as $key => $value) {
            $sheet->setCellValue('A' . $key + 2, $value->name . " " . $value->lastname);
            foreach ($accounts as $key2 => $acc) {
                $var = "cuenta" . $acc->id;
                $amount = $value->$var;
                if ($value->$var = "" || $value->$var == null) {
                    $amount = "-";
                    $sheet->getStyle($cells[$key2] . $key + 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                } else {
                    $sheet->getStyle($cells[$key2] . $key + 2)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
                    $sheet->getStyle($cells[$key2] . $key + 2)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                }
                $sheet->setCellValue($cells[$key2] . $key + 2, $amount);
            }
        }

        //nombre del archivo excel
        $file = "reporte_saldos_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    public function transactions_excel(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');

        $partner = "Todos";
        if ($request->partner_id) {
            $p = Partner::find($request->partner_id);
            $partner = $p->name . " " . $p->lastname;
        }

        if ($request->type_id == 0) {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                ->select(['transactions.*', 'partners.name'])
                ->where('transactions.company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->partner($request->partner_id)
                ->orderBy('date')->get();
        } else {
            $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                ->select(['transactions.*', 'partners.name'])
                ->where('transactions.company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->where('type', $request->type_id)
                ->partner($request->partner_id)
                ->orderBy('date')->get();
        }

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de transacciones'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $sheet->setCellValue('A1', 'DESDE');
        $sheet->setCellValue('B1', $desde);

        $sheet->getStyle("A2")->applyFromArray($styleArray);
        $sheet->setCellValue('A2', 'HASTA');
        $sheet->setCellValue('B2', $hasta);

        $sheet->getStyle("A3")->applyFromArray($styleArray);
        $sheet->setCellValue('A3', 'SOCIO');
        $sheet->setCellValue('B3', $partner);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'NÚMERO');

        $sheet->getStyle("B4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getStyle('B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B4', 'FECHA');

        $sheet->getStyle("C4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getStyle('C')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C4', 'TIPO');

        $sheet->getStyle("D4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getStyle('D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D4', 'SOCIO');

        $sheet->getStyle("E4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getStyle('E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E4', 'RUBROS');

        $sheet->getStyle("F4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getStyle('F')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('F4', 'REFERENCIA');

        $sheet->getStyle("G4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getStyle('G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('G4', 'TOTAL');

        $row = 1;
        $total = 0;
        foreach ($transactions as $key => $value) {
            $sheet->setCellValue('A' . $key + 5, $value->number);

            $sheet->setCellValue('B' . $key + 5, Carbon::parse($value->date)->format('d/m/Y'));
            if ($value->type == 1) {
                $sheet->setCellValue('C' . $key + 5, 'Individual');
            } elseif ($value->type == 2) {
                $sheet->setCellValue('C' . $key + 5, 'Lotes');
            } else {
                $sheet->setCellValue('C' . $key + 5, 'Pago');
            }

            $sheet->setCellValue('D' . $key + 5, $value->partner->name . " " . $value->partner->lastname);

            $sheet->getStyle('E')->getAlignment()->setWrapText(true);
            $accounts = "";
            foreach ($value->content as $account) {
                $accounts .= $account['name'] . "\n";
            }
            $sheet->setCellValue('E' . $key + 5, trim($accounts, "\n"));

            $sheet->setCellValue('F' . $key + 5, $value->reference);

            $sheet->getStyle('G' . $key + 5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('G' . $key + 5, $value->total);
            $row = $key + 5;
            $total += $value->total;
        }


        $sheet->getStyle('F' . $row + 1)->applyFromArray($styleArray);
        $sheet->setCellValue('F' . $row + 1, "TOTAL");

        $sheet->getStyle('G' . $row + 1)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->getStyle('G' . $row + 1)->applyFromArray($styleArray);
        $sheet->setCellValue('G' . $row + 1, $total);
        //nombre del archivo excel
        $file = "reporte_transacciones_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    public function transactionsReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $desde = Carbon::parse($request->from)->format('d/m/Y');
            $hasta = Carbon::parse($request->to)->format('d/m/Y');

            $partner = "Todos";
            if ($request->partner_id) {
                $p = Partner::find($request->partner_id);
                $partner = $p->name . " " . $p->lastname;
            }

            if ($request->type_id == 0) {
                $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                    ->select(['transactions.*', 'partners.name'])
                    ->where('transactions.company_id', session('company')->id)
                    ->whereBetween('date', [$request->from, $request->to])
                    ->partner($request->partner_id)
                    ->orderBy('date')->get();
            } else {
                $transactions = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                    ->select(['transactions.*', 'partners.name'])
                    ->where('transactions.company_id', session('company')->id)
                    ->whereBetween('date', [$request->from, $request->to])
                    ->where('type', $request->type_id)
                    ->partner($request->partner_id)
                    ->orderBy('date')->get();
            }


            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.transactions-pdf', ['transactions' => $transactions, 'from' => $desde, 'to' => $hasta, 'partner' => $partner])->setPaper('a4', 'landscape');
            //muestra el pdf    
            return $pdf->stream();
        } else {
            $this->transactions_excel($request);
        }
    }

    public function balancesReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $accounts = Account::all();

            $subquery = "";
            foreach ($accounts as $account) {
                $subquery .= '(SELECT ap.value FROM account_partner ap WHERE ap.partner_id = p.id AND ap.account_id = ' . $account->id . ') as "cuenta' . $account->id . '" ,';
            }
            $subquery = trim($subquery, ',');

            $balances = DB::select('SELECT p.id, p.code, p.name, p.lastname, p.identity, ' . $subquery . ' FROM partners p WHERE p.company_id = ' . session('company')->id . ' and p.status = 1 and (p.name LIKE "%' . $request->search . '%" or p.code LIKE "' . $request->search . '%" or p.identity LIKE "' . $request->search . '%")');

            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.partners-balances-pdf', ['balances' => $balances, 'accounts' => $accounts])->setPaper('a3', 'landscape');
            //muestra el pdf    
            return $pdf->stream();
        } else {
            $this->balances_excel($request);
        }
    }

    public function partners()
    {
        return view('reporting.partners');
    }

    public function partnersReporting(Request $request)
    {
        if ($request->type == "pdf") {
            $partners = Partner::whereStatus(Partner::ACTIVO)->whereCompanyId(session('company')->id)->get();
            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.partners-pdf', ['partners' => $partners]);
            return $pdf->stream();
        } else {
            $this->partners_excel();
        }
    }

    public function partners_excel()
    {
        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de socios'); //título de página

        $partners = Partner::whereStatus(Partner::ACTIVO)->whereCompanyId(session('company')->id)->get();

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true
            ]
        ];

        $styleAlignmentArray = [
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ]
        ];

        //Define el ancho de las columnas
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        //Aplica el arreglo de estilos al rango de celdas especificadas
        $sheet->getStyle("A1:F1")->applyFromArray($styleArray);
        $sheet->getStyle("A")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("B")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("C")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("D")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("E")->applyFromArray($styleAlignmentArray);
        $sheet->getStyle("F")->applyFromArray($styleAlignmentArray);

        //Celdas de título
        $sheet->setCellValue('A1', 'CÓDIGO');
        $sheet->setCellValue('B1', 'IDENTIFICACIÓN');
        $sheet->setCellValue('C1', 'NOMBRES');
        $sheet->setCellValue('D1', 'APELLIDOS');
        $sheet->setCellValue('E1', 'TELÉFONO');
        $sheet->setCellValue('F1', 'EMAIL');

        //LLena las celdas con la data
        foreach ($partners as $key => $value) {
            $sheet->setCellValue('A' . $key + 2, $value->code);
            $sheet->setCellValue('B' . $key + 2, $value->identity);
            $sheet->setCellValue('C' . $key + 2, $value->name);
            $sheet->setCellValue('D' . $key + 2, $value->lastname);
            $sheet->setCellValue('E' . $key + 2, $value->phone);
            $sheet->setCellValue('F' . $key + 2, $value->email);
        }

        //nombre del archivo excel
        $file = "reporte_socios_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo

    }

    public function accounts()
    {
        return view('reporting.accounts');
    }

    public function accountsReporting(Request $request)
    {
        $transactions = [];
        $total = 0;
        $partner = "Todos";

        if ($request->partner_id) {
            $p = Partner::find($request->partner_id);
            $partner = $p->name . " " . $p->lastname;
        }


        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');

        if ($request->type_id == 0) {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                ->select(['transactions.*', 'partners.name'])
                ->where('transactions.company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->partner($request->partner_id)
                ->orderBy('date')->get();
        } else {
            $trans = Transaction::join('partners', 'partners.id', 'transactions.partner_id')
                ->select(['transactions.*', 'partners.name'])
                ->where('transactions.company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->where('type', $request->type_id)
                ->partner($request->partner_id)
                ->orderBy('date')->get();
        }

        foreach ($trans as $value) {
            foreach ($value->content as $c) {
                if ($request->account_id != "") {
                    if ($c['id'] == $request->account_id) {
                        $transactions[] = [
                            'number' => $value->number,
                            'date' => $value->date,
                            'partner_name' => $value->partner->name,
                            'partner_lastname' => $value->partner->lastname,
                            'account' => $c['name'],
                            'value' => $c['price'],
                            'type' => $value->type,
                        ];
                        $total += $c['price'];
                    }
                }
            }
        }
        if ($request->type == "pdf") {
            //genera el pdf con el contenido de la transacción
            $pdf = PDF::loadView('reporting.accounts-pdf', ['transactions' => $transactions, 'from' => $desde, 'to' => $hasta, 'partner' => $partner, 'total' => $total])->setPaper('a4', 'landscape');
            //muestra el pdf    
            return $pdf->stream();
        } else {
            $this->accounts_excel($request, $transactions, $partner, $total);
        }
    }

    public function accounts_excel(Request $request, $transactions, $partner, $total)
    {

        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de cuentas'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $sheet->setCellValue('A1', 'DESDE');
        $sheet->setCellValue('B1', $desde);

        $sheet->getStyle("A2")->applyFromArray($styleArray);
        $sheet->setCellValue('A2', 'HASTA');
        $sheet->setCellValue('B2', $hasta);

        $sheet->getStyle("A3")->applyFromArray($styleArray);
        $sheet->setCellValue('A3', 'SOCIO');
        $sheet->setCellValue('B3', $partner);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'TRANS. #');

        $sheet->getStyle("B4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getStyle('B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B4', 'FECHA');

        $sheet->getStyle("C4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getStyle('C')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C4', 'TIPO');

        $sheet->getStyle("D4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getStyle('D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D4', 'SOCIO');

        $sheet->getStyle("E4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getStyle('E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E4', 'CUENTA');

        $sheet->getStyle("F4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getStyle('F')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('F4', 'VALOR');

        $current_row = 0;
        foreach ($transactions as $key => $value) {
            $sheet->setCellValue('A' . $key + 5, $value['number']);

            $sheet->setCellValue('B' . $key + 5, Carbon::parse($value['date'])->format('d/m/Y'));
            if ($value['type'] == 1) {
                $sheet->setCellValue('C' . $key + 5, 'Individual');
            } elseif ($value['type'] == 2) {
                $sheet->setCellValue('C' . $key + 5, 'Lotes');
            } else {
                $sheet->setCellValue('C' . $key + 5, 'Pago');
            }

            $sheet->setCellValue('D' . $key + 5, $value['partner_name'] . " " . $value['partner_lastname']);

            $sheet->getStyle('E')->getAlignment()->setWrapText(true);
            $sheet->setCellValue('E' . $key + 5, $value['account']);

            $sheet->getStyle('F' . $key + 5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('F' . $key + 5, $value['value']);
            $current_row = $key + 5;
        }

        $sheet->getStyle('E' . $current_row + 1)->applyFromArray($styleArray);
        $sheet->setCellValue('E' . $current_row + 1, "TOTAL");
        $sheet->getStyle('F' . $current_row + 1)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('F' . $current_row + 1, $total);

        //nombre del archivo excel
        $file = "reporte_cuentas_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    public function expenses()
    {
        return view('reporting.expenses');
    }

    public function expensesReporting(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');


        if ($request->supplier_id == "" || $request->supplier_id == null) {
            $expenses = Expense::where('company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->orderBy('date')->get();
        } else {
            $expenses = Expense::where('company_id', session('company')->id)
                ->whereBetween('date', [$request->from, $request->to])
                ->where('supplier_id', $request->supplier_id)
                ->orderBy('date')->get();
        }

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Reporte de facturas de compra'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        $sheet->getStyle("A1")->applyFromArray($styleArray);
        $sheet->setCellValue('A1', 'DESDE');
        $sheet->setCellValue('B1', $desde);

        $sheet->getStyle("A2")->applyFromArray($styleArray);
        $sheet->setCellValue('A2', 'HASTA');
        $sheet->setCellValue('B2', $hasta);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A4', 'No. FACTURA');

        $sheet->getStyle("B4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('B')->setWidth(30);
        $sheet->getStyle('B')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B4', 'No. AUTORIZACION');

        $sheet->getStyle("C4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getStyle('C')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C4', 'FECHA');

        $sheet->getStyle("D4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getStyle('D')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D4', 'PROVEEDOR');

        $sheet->getStyle("E4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getStyle('E')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E4', 'RUC/CI');

        $sheet->getStyle("F4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getStyle('F')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('F4', 'SUBTOTAL');

        $sheet->getStyle("G4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getStyle('G')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('G4', 'IVA');

        $sheet->getStyle("H4")->applyFromArray($styleArray);
        $sheet->getColumnDimension('H')->setWidth(15);
        $sheet->getStyle('H')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('H4', 'TOTAL');

        $row = 1;
        $total = 0;
        foreach ($expenses as $key => $value) {
            $sheet->setCellValue('A' . $key + 5, $value->number);

            $sheet->setCellValue('B' . $key + 5, $value->auth_number);

            $sheet->setCellValue('C' . $key + 5, Carbon::parse($value->date)->format('d/m/Y'));

            $sheet->setCellValue('D' . $key + 5, $value->suppliers->name);

            $sheet->setCellValue('E' . $key + 5, $value->suppliers->identity);

            $sheet->getStyle('F' . $key + 5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('F' . $key + 5, $value->total - $value->tax);

            $sheet->getStyle('G' . $key + 5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('G' . $key + 5, $value->tax);

            $sheet->getStyle('H' . $key + 5)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('H' . $key + 5, $value->total);
            $row = $key + 5;
            $total += $value->total;
        }


        $sheet->getStyle('G' . $row + 1)->applyFromArray($styleArray);
        $sheet->setCellValue('G' . $row + 1, "TOTAL");

        $sheet->getStyle('H' . $row + 1)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->getStyle('H' . $row + 1)->applyFromArray($styleArray);
        $sheet->setCellValue('H' . $row + 1, $total);
        //nombre del archivo excel
        $file = "reporte_facturas_compras_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    public function ledger()
    {
        return view('reporting.ledger');
    }

    function general_balance()
    {
        return view('reporting.balance');
    }

    function results()
    {
        return view('reporting.results');
    }

    function results_pdf(Request $request)
    {
        if ($request->type == 'pdf') {
            $results = new Results();

            $ingresos = $results->ingresos($request->from, $request->to);
            $otros_ingresos = $results->otros_ingresos($request->from, $request->to);
            $costos = $results->costos($request->from, $request->to);
            $gastos = $results->gastos($request->from, $request->to);
            $impuestos = $results->impuestos($request->from, $request->to);

            $desde = Carbon::parse($request->from)->format('d/m/Y');
            $hasta = Carbon::parse($request->to)->format('d/m/Y');
            $company = session('company');

            $pdf = PDF::loadView('reporting.results-pdf', [
                'ingresos' => $ingresos,
                'otros_ingresos' => $otros_ingresos,
                'costos' => $costos,
                'gastos' => $gastos,
                'impuestos' => $impuestos,
                'desde' => $desde,
                'hasta' => $hasta,
                'company'   => $company,
                'level' => $request->level
            ]);

            return $pdf->stream();
        } else {
            $this->results_excel($request);
        }
    }

    function results_excel(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');
        $company = session('company');
        $results = new Results();

        $ingresos = $results->ingresos($request->from, $request->to);
        $otros_ingresos = $results->otros_ingresos($request->from, $request->to);
        $costos = $results->costos($request->from, $request->to);
        $gastos = $results->gastos($request->from, $request->to);
        $impuestos = $results->impuestos($request->from, $request->to);

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Estado de resultados'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        // Encabezado
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B1")->applyFromArray($styleArray);
        $sheet->setCellValue('B1', 'ESTADO DE RESULTADOS');


        $sheet->getStyle("B2")->applyFromArray($styleArray);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('B2', $company->businessname);

        $sheet->getStyle("A3")->applyFromArray($styleArray);
        $sheet->setCellValue('A3', 'DESDE');
        $sheet->setCellValue('B3', $desde);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->setCellValue('A4', 'HASTA');
        $sheet->setCellValue('B4', $hasta);

        $sheet->getStyle("A5")->applyFromArray($styleArray);

        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A5', 'INGRESOS');

        $sheet->getColumnDimension('A')->setWidth(30);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(15);

        // INGRESOS
        $current_row = 6;
        $total_ingresos = 0;
        foreach ($ingresos as $key => $value) {

            if ($value['nivel'] == 1) {
                $total_ingresos = $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }
                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);
                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL INGRESOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_ingresos);

        $current_row++;
        $current_row++;

        // COSTOS DE VENTA
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'COSTO DE VENTAS');

        $current_row++;
        $total_costos = 0;

        foreach ($costos as $value) {
            if ($value['nivel'] == 1) {
                $total_costos = $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);
                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL COSTOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_costos);

        $current_row++;
        $current_row++;

        //UTILIDAD BRUTA
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "UTILIDAD BRUTA");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_ingresos - $total_costos);

        $current_row++;
        $current_row++;

        // GASTOS
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'GASTOS');
        $current_row++;
        $total_gastos = 0;

        foreach ($gastos as $value) {
            if ($value['nivel'] == 1) {
                $total_gastos = $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);
                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL GASTOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_gastos);

        $current_row++;
        $current_row++;

        //UTILIDAD OPERATIVA
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "UTILIDAD OPERATIVA");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_ingresos - $total_costos - $total_gastos);

        $current_row++;
        $current_row++;

        // OTROS INGRESOS
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'OTROS INGRESOS');
        $current_row++;
        $total_otros_ingresos = 0;

        foreach ($otros_ingresos as $value) {
            if ($value['nivel'] == 1) {
                $total_otros_ingresos = $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);
                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL OTROS INGRESOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_otros_ingresos);

        $current_row++;
        $current_row++;

        //UTILIDAD ANTES DE IMPUESTO
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "UTILIDAD ANTES DE IMPUESTOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_ingresos - $total_costos - $total_gastos + $total_otros_ingresos);

        $current_row++;
        $current_row++;

        // IMPUESTOS
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'IMPUESTOS');
        $current_row++;
        $total_impuestos = 0;

        foreach ($impuestos as $value) {
            if ($value['nivel'] == 1) {
                $total_impuestos = $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);
                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL IMPUESTOS");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_impuestos);

        $current_row++;
        $current_row++;

        //UTILIDAD ANTES DE IMPUESTO
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "UTILIDAD NETA");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_ingresos - $total_costos - $total_gastos + $total_otros_ingresos - $total_impuestos);

        //nombre del archivo excel
        $file = "estado_resultados_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    function general_balance_pdf(Request $request)
    {
        if ($request->type == 'pdf') {
            $balance = new Balance();

            $activo = $balance->activo($request->from, $request->to);
            $pasivo = $balance->pasivo($request->from, $request->to);
            $patrimonio = $balance->patrimonio($request->from, $request->to);

            $desde = Carbon::parse($request->from)->format('d/m/Y');
            $hasta = Carbon::parse($request->to)->format('d/m/Y');
            $company = session('company');

            $pdf = PDF::loadView('reporting.general-balance-pdf', [
                'activo' => $activo,
                'pasivo' => $pasivo,
                'patrimonio' => $patrimonio,
                'desde' => $desde,
                'hasta' => $hasta,
                'company'   => $company,
                'level' => $request->level
            ]);

            return $pdf->stream();
        } else {
            $this->general_balance_excel($request);
        }
    }

    function general_balance_excel(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');
        $company = session('company');

        $balance = new Balance();

        $activo = $balance->activo($request->from, $request->to);
        $pasivo = $balance->pasivo($request->from, $request->to);
        $patrimonio = $balance->patrimonio($request->from, $request->to);

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Estado de resultados'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        // Encabezado
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B1")->applyFromArray($styleArray);
        $sheet->setCellValue('B1', 'ESTADO DE SITUACIÓN INICIAL');


        $sheet->getStyle("B2")->applyFromArray($styleArray);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('B2', $company->businessname);

        $sheet->getStyle("A3")->applyFromArray($styleArray);
        $sheet->setCellValue('A3', 'DESDE');
        $sheet->setCellValue('B3', $desde);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->setCellValue('A4', 'HASTA');
        $sheet->setCellValue('B4', $hasta);

        $sheet->getStyle("A5")->applyFromArray($styleArray);

        $sheet->getStyle('A')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('A5', 'ACTIVO');

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(15);

        // ACTIVO
        $current_row = 6;
        $total_activo = 0;
        foreach ($activo as $value) {
            if ($value['nivel'] == 1) {
                $total_activo += $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);

                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL ACTIVO");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_activo);

        $current_row++;
        $current_row++;

        // PASIVO
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'PASIVO');
        $current_row++;

        $total_pasivo = 0;
        foreach ($pasivo as $value) {
            if ($value['nivel'] == 1) {
                $total_pasivo += $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);

                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL PASIVO");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_pasivo);

        $current_row++;
        $current_row++;

        // PATRIMONIO
        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, 'PASIVO');
        $current_row++;

        $total_patrimonio = 0;
        foreach ($patrimonio as $value) {
            if ($value['nivel'] == 1) {
                $total_patrimonio += $value['total'];
            }

            if ($value['nivel'] <= $request->level) {
                if ($value['grupo'] == 1) {
                    $sheet->getStyle('A' . $current_row . ':' . 'C' . $current_row)->applyFromArray($styleArray);
                }

                $sheet->setCellValue('A' . $current_row, $value['codigo']);

                $sheet->setCellValue('B' . $current_row, $value['cuenta']);

                $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
                $sheet->setCellValue('C' . $current_row, $value['total']);

                $current_row++;
            }
        }

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "TOTAL PATRIMONIO");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_patrimonio);

        $current_row++;
        $current_row++;

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->setCellValue('A' . $current_row, "PASIVO + PATRIMONIO");

        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->setCellValue('C' . $current_row, $total_pasivo + $total_patrimonio);

        //nombre del archivo excel
        $file = "estado_situacion_inicial_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }

    function ledger_pdf(Request $request)
    {
        if ($request->type == 'pdf') {
            if ($request->accounting_id == null || $request->accounting_id == "") {
                $this->info('Debe seleccionar una cuenta contable.');
                return;
            }

            $accounting = Accounting::find($request->accounting_id);

            // $account_type = Accounting::find($request->accounting_id)->type;

            $journals = JournalDetail::join('journals', 'journals.id', 'journal_details.journal_id')
                ->select('journals.date', 'journals.refence as reference', 'journal_details.debit_value', 'journal_details.credit_value')
                ->where('journals.company_id', session('company')->id)
                ->where('journals.state', '1')
                ->whereBetween('journals.date', [$request->from, $request->to])
                ->where('journal_details.accounting_id', $request->accounting_id)
                ->orderBy('journals.date')
                ->get();

            if (count($journals) != 0) {
                $desde = Carbon::parse($request->from)->format('d/m/Y');
                $hasta = Carbon::parse($request->to)->format('d/m/Y');
                $company = session('company');

                $pdf = PDF::loadView('reporting.ledger-pdf', [
                    'journals' => $journals,
                    'accounting' => $accounting,
                    'desde' => $desde,
                    'hasta' => $hasta,
                    'company'   => $company
                ]);

                return $pdf->stream();
            }
        } else {
            $this->ledger_excel($request);
        }
    }

    function ledger_excel(Request $request)
    {
        $desde = Carbon::parse($request->from)->format('d/m/Y');
        $hasta = Carbon::parse($request->to)->format('d/m/Y');
        $company = session('company');
        $accounting = Accounting::find($request->accounting_id);

        //instancia el archivo de excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Estado de resultados'); //título de página               

        //arreglo de estilos
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'wrapText' => true
            ],
        ];

        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(45);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);

        // Encabezado
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B1")->applyFromArray($styleArray);
        $sheet->setCellValue('B1', 'LIBRO MAYOR');


        $sheet->getStyle("B2")->applyFromArray($styleArray);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->setCellValue('B2', $company->businessname);

        $sheet->getStyle("A3")->applyFromArray($styleArray);
        $sheet->setCellValue('A3', 'DESDE');
        $sheet->setCellValue('B3', $desde);

        $sheet->getStyle("A4")->applyFromArray($styleArray);
        $sheet->setCellValue('A4', 'HASTA');
        $sheet->setCellValue('B4', $hasta);

        $sheet->getStyle("A5")->applyFromArray($styleArray);
        $sheet->setCellValue('A5', 'Cuenta:');
        $sheet->setCellValue('B5', $accounting->code . ' ' . $accounting->name);


        //Celdas de título
        $sheet->setCellValue('A6', 'FECHA');
        $sheet->setCellValue('B6', 'CONCEPTO');
        $sheet->setCellValue('C6', 'DEBE');
        $sheet->setCellValue('D6', 'HABER');
        $sheet->setCellValue('E6', 'SALDO');
        $sheet->getStyle("A6")->applyFromArray($styleArray);
        $sheet->getStyle("B6")->applyFromArray($styleArray);
        $sheet->getStyle("C6")->applyFromArray($styleArray);
        $sheet->getStyle("D6")->applyFromArray($styleArray);
        $sheet->getStyle("E6")->applyFromArray($styleArray);

        $journals = JournalDetail::join('journals', 'journals.id', 'journal_details.journal_id')
            ->select('journals.date', 'journals.refence as reference', 'journal_details.debit_value', 'journal_details.credit_value')
            ->where('journals.company_id', session('company')->id)
            ->where('journals.state', '1')
            ->whereBetween('journals.date', [$request->from, $request->to])
            ->where('journal_details.accounting_id', $request->accounting_id)
            ->orderBy('journals.date')
            ->get();

        $current_row = 7;
        $total_debe = 0;
        $total_haber = 0;
        //LLena las celdas con la data
        foreach ($journals as $key => $value) {
            $sheet->setCellValue('A' . $current_row, $value->date);
            $sheet->setCellValue('B' . $current_row, $value->reference);
            $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('C' . $current_row, $value->debit_value);
            $sheet->getStyle('D' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
            $sheet->setCellValue('D' . $current_row, $value->credit_value);
            $total_debe = $total_debe + $value->debit_value;
            $total_haber = $total_haber + $value->credit_value;
            $current_row++;
        }


        $sheet->getStyle('C' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->getStyle('D' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);
        $sheet->getStyle('E' . $current_row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_ACCOUNTING_USD);

        $sheet->getStyle('A' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('C' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('D' . $current_row)->applyFromArray($styleArray);
        $sheet->getStyle('E' . $current_row)->applyFromArray($styleArray);

        $sheet->setCellValue('A' . $current_row, 'TOTALES');
        $sheet->setCellValue('C' . $current_row, $total_debe);
        $sheet->setCellValue('D' . $current_row, $total_haber);
        $sheet->setCellValue('E' . $current_row, $total_debe - $total_haber);
        //nombre del archivo excel
        $file = "libro_mayor_" . date('dmYHi') . ".xlsx";

        //cabeceras para archivos xlsx
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $file . '"');
        header('Cache-Control: max-age=0');

        //escribe el contenido en el archivo xlsx
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output'); //descarga el archivo
    }
}
