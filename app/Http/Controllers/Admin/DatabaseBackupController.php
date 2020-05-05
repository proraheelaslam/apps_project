<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class DatabaseBackupController extends Controller
{
    //

    public function index()
    {
        return view('admin.db_backup.index');

    }

    public function dumpMongoDatabase()
    {
          $path =   storage_path('app/mongodump.sh');

          $process = Process::fromShellCommandline($path);
          $process->run();

          if (!$process->isSuccessful()) {
              throw new  ProcessFailedException($process);
          }
          echo  $process->getOutput();
          return "backup has been successfully";
    }
}
