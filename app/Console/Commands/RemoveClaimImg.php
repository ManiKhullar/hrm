<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RemoveClaimImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'claimimage:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old claim images from the folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = date('Y-m-d H:i:s', strtotime('-2 months'));
        $images = DB::select("SELECT * FROM claim_images WHERE DATE(created_at) <= '$date'");
        
        if(!empty($images)){
            foreach ($images as $image) {
                $this->deleteFile($image->file_upload);
            }
        }
        // DB::select("DELETE FROM claim_images WHERE DATE(created_at) <= '$date'");
    }

    public function deleteFile($fileName)
    {
        $path = public_path('claims/images/');
        $filePath = $path . $fileName;
        chmod($filePath, 0755); 

        // Using File facade
        if (File::exists($filePath)) {
            File::delete($filePath);
            return response()->json(['message' => 'File deleted successfully'], 200);
        }

        return response()->json(['message' => 'File not found'], 404);
    }
    
}
