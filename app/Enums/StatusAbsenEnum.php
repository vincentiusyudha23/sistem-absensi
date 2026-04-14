<?php

namespace App\Enums;


class StatusAbsenEnum
{
    public static function statusHariIni($val)
    {
        $output = '';

        if($val == 0 || $val == null){
            $output = '
                <div class="card bg-secondary mb-2 border-0" style="--bs-bg-opacity: .5;">
                    <div class="card-body">
                        <h5 class="fw-bold mb-1">Status hari ini</h5>

                        <hr class="m-0 p-0">

                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                            <span class="badge bg-danger fs-5">
                                X
                            </span>

                            <h3 class="fw-bold mb-0">Belum Absen</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        if($val == 1){
            $output = '
                <div class="card bg-primary mb-2 border-0">
                    <div class="card-body text-white">
                        <h5 class="fw-bold mb-1">Status hari ini</h5>

                        <hr class="m-0 p-0">

                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                            <span class="badge text-bg-light">
                                <i class="bi bi-check2 fs-3 text-success"></i>
                            </span>

                            <h3 class="fw-bold text-white mb-0">Hadir</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        if($val == 2){
            $output = '
                <div class="card bg-warning mb-2 border-0">
                    <div class="card-body text-white">
                        <h5 class="fw-bold mb-1">Status hari ini</h5>

                        <hr class="m-0 p-0">

                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                            <span class="badge text-bg-light text-warning">
                                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                            </span>

                            <h3 class="fw-bold text-white mb-0">Terlambat</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        if($val == 3){
            $output = '
                <div class="card bg-danger mb-2 border-0">
                    <div class="card-body text-white">
                        <h5 class="fw-bold mb-1">Status hari ini</h5>

                        <hr class="m-0 p-0">

                        <div class="d-flex flex-wrap align-items-center mt-3 gap-2">
                            <span class="badge bg-light text-danger fs-5">
                                X
                            </span>

                            <h3 class="fw-bold text-white mb-0">Tidak Hadir</h3>
                        </div>
                    </div>
                </div>
            ';
        }

        return $output;
    }

    public static function getBadgeStatusAbsen($val)
    {
        return match($val){
            1 => '<span class="badge text-bg-success">Tepat Waktu</span>',
            2 => '<span class="badge text-bg-warning">Terlambat</span>',
            3 => '<span class="badge text-bg-warning">Tidak Hadir</span>',
            default => ''
        };
    }
}