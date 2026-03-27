<?php

namespace App\Livewire;

use App\Models\PrinterSetting;
use Livewire\Component;

class PrinterManager extends Component
{
    public $paperSize = '58mm';
    public $printerName = 'Printer Kasir 1';
    public $headerText = '';
    public $footerText = 'Terima Kasih Atas Kunjungan Anda';
    public $autoPrint = false;

    public function mount()
    {
        // Ambil pengaturan milik tenant (Cafe) ini, jika belum ada, buatkan default-nya
        $setting = PrinterSetting::firstOrCreate(
            ['tenant_id' => auth()->user()->tenant_id],
            [
                'paper_size' => '58mm',
                'printer_name' => 'Printer Kasir 1',
                'footer_text' => 'Terima Kasih Atas Kunjungan Anda',
                'auto_print' => false,
            ]
        );

        $this->paperSize = $setting->paper_size;
        $this->printerName = $setting->printer_name;
        $this->headerText = $setting->header_text;
        $this->footerText = $setting->footer_text;
        $this->autoPrint = $setting->auto_print;
    }

    public function saveSettings()
    {
        $this->validate([
            'paperSize' => 'required|in:58mm,80mm',
            'printerName' => 'required|string|max:50',
            'headerText' => 'nullable|string|max:100',
            'footerText' => 'nullable|string|max:100',
            'autoPrint' => 'boolean',
        ]);

        PrinterSetting::where('tenant_id', auth()->user()->tenant_id)->update([
            'paper_size' => $this->paperSize,
            'printer_name' => $this->printerName,
            'header_text' => $this->headerText,
            'footer_text' => $this->footerText,
            'auto_print' => $this->autoPrint,
        ]);

        session()->flash('success', 'Pengaturan printer dan struk berhasil disimpan!');
    }

    public function render()
    {
        return view('livewire.printer-manager')->layout('components.layouts.app');
    }
}