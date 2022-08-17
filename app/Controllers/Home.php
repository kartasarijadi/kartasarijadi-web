<?php

namespace App\Controllers;


class Home extends BaseController
{
    private $lm, $am, $mm, $hm, $msm;
    public function __construct()
    {
        $this->lm = new \App\Models\LandingModel();
        $this->am = new \App\Models\ActivitiesModel();
        $this->mm = new \App\Models\MembersModel();
        $this->hm = new \App\Models\HistoryModel();
        $this->msm = new \App\Models\MessagesModel();
    }
    public function index()
    {
        $data = [
            'title' => 'Halaman Utama | Karta Sarijadi',
            'landingInfo' => $this->lm->find(1, true),
            'activitiesInfo' => $this->am->find(1, true),
            'members' => $this->mm->getMembers()
        ];
        return view('home/index', $data);
    }
    public function history()
    {
        $data = [
            'title' => 'Sejarah Kami | Karta Sarijadi',
            'historyInfo' => $this->hm->find(1, true)
        ];
        return view('home/history', $data);
    }

    public function contactUs()
    {
        helper('form');
        if (getMethod('post')) {
            return $this->_sendMessage();
        }
        $data = [
            'title' => 'Hubungi Kami | Karta Sarijadi'
        ];
        return view('home/contact_us', $data);
    }

    public function sitemap()
    {
        $this->response->setContentType('application/xml', 'ISO-8859-1');
        return view('home/sitemap');
    }

    private function _sendMessage()
    {
        if ($referrer = acceptFrom('hubungi-kami')) {
            return redirect()->to($referrer);
        }
        $rules = $this->msm->getValidationRules(['add' => ['gRecaptcha']]);
        if (!$this->validate($rules)) {
            return redirect()->to('hubungi-kami')->withInput();
        }
        $whatsappNumber = strval($this->request->getPost('message_whatsapp'));
        if ($whatsappNumber[0] == '0') {
            $whatsappNumber[0] = '2';
            $whatsappNumber = '6' . $whatsappNumber;
        }
        if ($whatsappNumber[0] == '+') {
            $whatsappNumber = str_replace('+', '', $whatsappNumber);
        }
        if ($whatsappNumber[0] == '8') {
            $whatsappNumber[0] = '8';
            $whatsappNumber = '62' . $whatsappNumber;
        }
        $data = [
            'message_sender' => $this->request->getPost('message_sender'),
            'message_whatsapp' => $whatsappNumber,
            'message_type' => $this->request->getPost('message_type'),
            'message_text' => $this->request->getPost('message_text')
        ];
        if ($this->msm->save($data)) {
            $this->_sendNotification($data);
            $flash = [
                'message' => 'Berhasil mengirimkan pesan.',
                'type' => 'success'
            ];
            setFlash($flash);
        } else {
            $flash = [
                'message' => 'Gagal mengirimkan pesan.',
                'type' => 'danger'
            ];
            setFlash($flash);
        }
        return redirect()->to('hubungi-kami');
    }

    private function _sendNotification($data)
    {
        $um = new \App\Models\UsersModel();
        $admin = $um->find(1, true);
        $config = [
            'protocol' => getenv('email.protocol'),
            'SMTPHost' => 'mail.kartasarijadi.com',
            'SMTPUser' => 'no-reply@kartasarijadi.com',
            'mailType' => 'html',
            'SMTPPass' => getenv('email.pass'),
            'SMTPPort' => getenv('email.port'),
            'mailType' => 'html',
        ];
        $email = \Config\Services::email($config);
        $email->setFrom('no-reply@kartasarijadi.com', 'No Reply - Karang Taruna Sarijadi');
        $email->setTo($admin->user_email);

        $email->setSubject('Verifikasi Proses Atur Ulang Kata Sandi');
        $data = [
            'name' => $admin->user_name,
            'message_sender' => $data['message_sender'],
            'message_whatsapp' => 'https://wa.me/' . $data['message_whatsapp'],
            'message_type' => $data['message_type'],
            'message_text' => $data['message_text']
        ];
        $email->setMessage(view('layout/email/messages', $data));
        return $email->send(true);
    }
}
