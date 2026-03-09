<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Rental;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name'         => 'Admin Kost',
            'email'        => 'admin@kostsejahtera.com',
            'password'     => Hash::make('admin123'),
            'role'         => 'admin',
            'phone'        => '0812-3456-7890',
            'kost_name'    => 'Kost Sejahtera',
            'kost_address' => 'Jl. Setia Budi No. 123, Medan',
        ]);

        // Rooms
        $rooms = [
            ['name'=>'VVIP 01','type'=>'VVIP','price'=>1000000,'status'=>'available','facilities'=>json_encode(['AC','WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'VVIP 02','type'=>'VVIP','price'=>1000000,'status'=>'occupied', 'facilities'=>json_encode(['AC','WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'VVIP 03','type'=>'VVIP','price'=>1000000,'status'=>'occupied', 'facilities'=>json_encode(['AC','WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'VIP 01', 'type'=>'VIP', 'price'=>800000, 'status'=>'available','facilities'=>json_encode(['WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'VIP 02', 'type'=>'VIP', 'price'=>800000, 'status'=>'occupied', 'facilities'=>json_encode(['WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'VIP 03', 'type'=>'VIP', 'price'=>800000, 'status'=>'available','facilities'=>json_encode(['WiFi','Spring Bed','Lemari','Air','Listrik'])],
            ['name'=>'Reguler 01','type'=>'Reguler','price'=>500000,'status'=>'occupied','facilities'=>json_encode(['Kipas','Lemari','Air','Listrik'])],
            ['name'=>'Reguler 02','type'=>'Reguler','price'=>500000,'status'=>'occupied','facilities'=>json_encode(['Kipas','Lemari','Air','Listrik'])],
            ['name'=>'Reguler 03','type'=>'Reguler','price'=>500000,'status'=>'occupied','facilities'=>json_encode(['Kipas','Lemari','Air','Listrik'])],
        ];
        foreach ($rooms as $r) Room::create($r);

        // Tenants
        $tenants = [
            ['name'=>'Reza Pahlepi',  'phone'=>'083133387676','emergency_contact'=>'081254367654','ktp_number'=>'1234567890123456','status'=>'active','joined_date'=>'2026-01-28'],
            ['name'=>'Putri Amalia',  'phone'=>'087678764577','emergency_contact'=>'087898557866','ktp_number'=>'9876543210987654','status'=>'active','joined_date'=>'2026-01-28'],
            ['name'=>'Yanti Putri',   'phone'=>'086567876532','emergency_contact'=>'081273647382','ktp_number'=>'1122334455667788','status'=>'active','joined_date'=>'2026-01-28'],
            ['name'=>'Anto Pratama',  'phone'=>'087623762733','emergency_contact'=>'087678765434','ktp_number'=>'5544332211009988','status'=>'active','joined_date'=>'2026-01-28'],
            ['name'=>'Budi Siregar',  'phone'=>'081234567890','emergency_contact'=>'082345678901','ktp_number'=>'6677889900112233','status'=>'active','joined_date'=>'2026-01-28'],
            ['name'=>'Rangga Aditya', 'phone'=>'089876543210','emergency_contact'=>'081122334455','ktp_number'=>'9988776655443322','status'=>'active','joined_date'=>'2026-01-28'],
        ];
        foreach ($tenants as $t) Tenant::create($t);

        // Rentals & Payments
        $data = [
            [2, 1, '2026-01-28', '2026-03-28', 2, 2000000],
            [5, 2, '2026-01-28', '2026-02-28', 1, 800000],
            [3, 3, '2026-01-28', '2026-02-28', 1, 1000000],
            [9, 4, '2026-01-28', '2026-02-28', 1, 500000],
            [8, 5, '2026-01-28', '2026-02-28', 1, 500000],
            [7, 6, '2026-01-28', '2026-02-28', 1, 500000],
        ];
        foreach ($data as $i => [$rid, $tid, $start, $end, $months, $total]) {
            $rental = Rental::create([
                'room_id'     => $rid,
                'tenant_id'   => $tid,
                'start_date'  => $start,
                'end_date'    => $end,
                'months'      => $months,
                'total_price' => $total,
                'status'      => 'active',
            ]);
            Payment::create([
                'rental_id'      => $rental->id,
                'tenant_id'      => $tid,
                'room_id'        => $rid,
                'invoice_number' => 'INV-'.date('Ymd').'-'.str_pad($rental->id, 4, '0', STR_PAD_LEFT),
                'amount'         => $total,
                'due_date'       => $end,
                'paid_date'      => '2026-01-28',
                'status'         => 'paid',
                'payment_method' => 'Transfer Bank',
            ]);
        }

        // Expenses
        Expense::create(['date'=>'2026-01-28','description'=>'Tagihan Listrik Januari','amount'=>500000,'category'=>'Utilitas']);
        Expense::create(['date'=>'2026-02-01','description'=>'Pemeliharaan AC Kamar VVIP','amount'=>300000,'category'=>'Maintenance']);
        Expense::create(['date'=>'2026-02-10','description'=>'Biaya Air Februari','amount'=>200000,'category'=>'Utilitas']);
    }
}
