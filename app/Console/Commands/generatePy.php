<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpParser\Node\Expr\ShellExec;

class generatePy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-py';

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
        // Wrote by hoangmen
        if (!file_exists(base_path('.venv_clip'))) {
            if ($this->confirm('Setup môi trường ảo Python? (Không cần chạy nếu đã chạy rồi)', true)) {

                $isWin = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
                $pythonCmd = $isWin ? 'py -3.13' : 'python3.13';

                $this->info("Đang tạo môi trường ảo: $pythonCmd -m venv .venv_clip");

                $output = shell_exec("$pythonCmd -m venv .venv_clip 2>&1");




                if (is_dir(base_path('.venv_clip'))) {
                    $this->info('Đã tạo xong môi trường ảo .venv_clip');
                    if ($this->confirm('Bạn có muốn tiếp tục cài đặt các gói (package) hỗ trợ Source? (Sẽ khá tốn thời gian, nhưng cần thiết)', true)) {
                        $this->info('Đang tải các gói thư viện..');
                        $pip = $isWin ? '.venv_clip\\Scripts\\pip.exe' : '.venv_clip/bin/pip';

                        exec("$pip install -r requirements.txt 2>&1", $lines, $exitCode);
                        $output = implode("\n", $lines);
                        if (str_contains($output, 'Successfully installed') || str_contains($output, 'Requirement already satisfied')) {
                            $this->info("Đã cài đặt xong");
                        } else {
                            $this->error("Có lỗi xảy ra khi cài đặt");
                            $this->line($output);
                            $this->warn("Đang thử cập nhật pip trong môi trường ảo...");
                            $python = $isWin ? '.venv_clip\\Scripts\\python.exe' : '.venv_clip/bin/python';
                            $updateOutput = shell_exec("$python -m pip install --upgrade pip 2>&1");

                            if (str_contains($updateOutput, 'Successfully installed') || str_contains($updateOutput, 'Requirement already satisfied')) {
                                $pipVersion = shell_exec("$python -m pip --version");
                                $this->line("📦 Phiên bản pip hiện tại: " . trim($pipVersion));
                                $this->info("Đã cập nhật pip trong môi trường ảo, vui lòng xóa .vnev_clip và chạy lại lệnh");
                            } else {
                                $this->error("Cập nhật pip thất bại:");
                                $this->line($updateOutput);
                            }
                        }
                    }
                } else {
                    $this->error("Tạo môi trường ảo thất bại:\n" . $output);
                }
            } else {
                $this->warn('Đã dùng.');
            }
        } else {
            $this->warn('Môi trường ảo .venv_clip đã tồn tại, không cần tạo lại. Nếu setup sai, hãy xóa và chạy lại');
        }
    }
}
