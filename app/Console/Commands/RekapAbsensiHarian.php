<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RekapAbsensiHarian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:rekap-absensi-harian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now();

        $userSudahAbsen = Absensi::whereDate('created_at', $today)->pluck('user_id');
        $users = User::where('role', 'user')->whereNotIn('id', $userSudahAbsen)->get();

        foreach ($users as $user) {
            Absensi::create([
                'user_id' => $user->id,
                'status' => 3,
                'created_at' => $today,
                'updated_at' => now(),
            ]);
        }
    }
}
