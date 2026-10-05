<?php

namespace App\Controllers;

use App\Models\Race;
use App\Models\RaceYear;

class FormulareRaceYear extends BaseController
{
    protected $helpers = ['form'];

    /**
     * Přidání ročníku: zadává se jen real_name, id_race (mužský závod kategorie E) a logo.
     */
    public function create()
    {
        $real_name = trim((string) $this->request->getPost('real_name'));
        $id_race   = $this->request->getPost('id_race');

        $rules = [
            'real_name' => ['label' => 'Název ročníku', 'rules' => 'required|max_length[255]'],
            'id_race'   => ['label' => 'Závod', 'rules' => 'required|is_natural_no_zero'],
            'logo'      => ['label' => 'Logo', 'rules' => 'uploaded[logo]|is_image[logo]|max_size[logo,2048]|ext_in[logo,png,jpg,jpeg,webp]'],
        ];
        $messages = [
            'real_name' => ['required' => 'Vyplňte název ročníku.', 'max_length' => 'Název může mít nejvýše 255 znaků.'],
            'id_race'   => ['required' => 'Vyberte závod.', 'is_natural_no_zero' => 'Vyberte platný závod.'],
            'logo'      => ['uploaded' => 'Vyberte logo.', 'is_image' => 'Logo musí být obrázek.', 'max_size' => 'Logo může mít nejvýše 2 MB.', 'ext_in' => 'Použijte PNG, JPG nebo WEBP.'],
        ];

        if (!$this->validateData(['real_name' => $real_name, 'id_race' => $id_race], $rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $race = (new Race())->getMaleEliteRace((int) $id_race);
        if (!$race) {
            return redirect()->back()->withInput()->with('errors', ['Vyberte mužský závod kategorie E (Elite).']);
        }

        $raceyear = new RaceYear();
        $year     = (int) date('Y');
        $logo     = $this->request->getFile('logo');
        $dir      = FCPATH . 'uploads/logos';

        try {
            $newId = $raceyear->insert([
                'real_name'  => $real_name,
                'id_race'    => (int) $id_race,
                'year'       => $year,
                'start_date' => $year . '-01-01',
                'end_date'   => $year . '-01-01',
                'uci_tour'   => 0,
                'logo'       => '',
                'sex'        => 'M',
                'category'   => 'E',
                'country'    => $race->country,
            ]);

            // Logo se ukládá jako logo-{id}.přípona, stejně jako u stávajících ročníků
            $fileName = 'logo-' . $newId . '.' . strtolower($logo->getClientExtension());
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $logo->move($dir, $fileName, true);
            $raceyear->update($newId, ['logo' => $fileName]);
        } catch (\Throwable $e) {
            log_message('error', 'Přidání ročníku: {message}', ['message' => $e->getMessage()]);
            if (!empty($newId)) {
                $raceyear->delete($newId);
            }
            return redirect()->back()->withInput()->with('errors', ['Ročník se nepodařilo uložit. Zkuste to znovu.']);
        }

        return redirect()->to(base_url('rocniky/' . $id_race))->with('success', 'Ročník byl úspěšně přidán.');
    }
}
