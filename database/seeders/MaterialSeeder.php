<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::role('guru')->get();

        $subjects = Subject::with('classroom')->get();

        $youtubeLink = 'https://youtu.be/VULHwJ3KqdI?si=4iUdt8_mbf_Kylw6';

        $descriptions = [
            'Fiqih' => 'Fiqih adalah ilmu yang mempelajari tata cara beribadah dan berperilaku sesuai ajaran Islam agar dapat dilaksanakan dengan benar dalam kehidupan sehari-hari.',
            'SKI' => 'SKI atau Sejarah Kebudayaan Islam adalah pelajaran yang membahas sejarah perkembangan Islam, perjuangan Rasulullah SAW, para sahabat, dan tokoh-tokoh Islam.',
            'Aqidah' => 'Aqidah adalah keyakinan dasar dalam Islam yang harus diyakini oleh setiap muslim, seperti iman kepada Allah, malaikat, kitab, rasul, hari akhir, dan takdir.',
            'Akhlak' => 'Akhlak adalah pelajaran yang membahas perilaku baik dan buruk dalam kehidupan sehari-hari sesuai ajaran Islam.',
            'Bahasa Arab' => 'Bahasa Arab adalah pelajaran yang membantu siswa mengenal kosakata, kalimat, dan ungkapan sederhana dalam bahasa Arab.',
            'Al-Qur\'an' => 'Al-Qur\'an adalah pelajaran yang membimbing siswa membaca, memahami, dan mengamalkan ayat-ayat Al-Qur\'an dengan baik.',
            'Hadist' => 'Hadist adalah pelajaran yang membahas perkataan, perbuatan, dan keteladanan Rasulullah SAW sebagai pedoman hidup umat Islam.',
            'Safinah' => 'Safinah adalah pelajaran dasar tentang ilmu fiqih yang membahas pokok-pokok ibadah dan hukum Islam secara sederhana.',
            'Kaligrafi' => 'Kaligrafi adalah seni menulis huruf Arab dengan indah, rapi, dan sesuai kaidah penulisan.',
            'Tahfidz Juz 30' => 'Tahfidz Juz 30 adalah kegiatan menghafal surat-surat pendek dalam Al-Qur\'an agar siswa terbiasa membaca dan menjaga hafalannya.',
        ];

        $examples = [
            'Fiqih' => [
                'Cara berwudhu yang benar sebelum shalat.',
                'Tata cara melaksanakan shalat lima waktu.',
                'Tata cara berpuasa di bulan Ramadan.',
                'Menjaga kebersihan diri dan pakaian saat beribadah.',
            ],
            'SKI' => [
                'Mengenal sejarah Nabi Muhammad SAW.',
                'Mengetahui perjuangan para sahabat.',
                'Memahami perkembangan Islam pada masa awal.',
                'Meneladani tokoh-tokoh Islam.',
            ],
            'Aqidah' => [
                'Mengenal rukun iman.',
                'Meyakini Allah sebagai Tuhan Yang Maha Esa.',
                'Memahami tugas malaikat.',
                'Menanamkan keyakinan kepada hari akhir.',
            ],
            'Akhlak' => [
                'Bersikap sopan kepada orang tua dan guru.',
                'Membiasakan berkata jujur.',
                'Menolong teman yang membutuhkan.',
                'Menjaga kebersihan lingkungan.',
            ],
            'Bahasa Arab' => [
                'Mengenal kosakata benda di sekitar.',
                'Membaca kalimat sederhana.',
                'Menghafal mufradat harian.',
                'Melatih pelafalan huruf Arab.',
            ],
            'Al-Qur\'an' => [
                'Membaca huruf hijaiyah dengan benar.',
                'Melatih tajwid sederhana.',
                'Membaca surat-surat pendek.',
                'Membiasakan membaca Al-Qur\'an setiap hari.',
            ],
            'Hadist' => [
                'Menghafal hadist pendek.',
                'Memahami makna hadist tentang kebersihan.',
                'Meneladani perilaku Rasulullah SAW.',
                'Mengamalkan hadist dalam kehidupan sehari-hari.',
            ],
            'Safinah' => [
                'Mengenal dasar-dasar thaharah.',
                'Memahami syarat sah shalat.',
                'Mempelajari rukun Islam.',
                'Mengenal hukum ibadah sederhana.',
            ],
            'Kaligrafi' => [
                'Menulis huruf hijaiyah dengan rapi.',
                'Mengenal bentuk dasar khat.',
                'Melatih keindahan tulisan Arab.',
                'Membuat tulisan Arab sederhana.',
            ],
            'Tahfidz Juz 30' => [
                'Menghafal surat An-Nas.',
                'Mengulang hafalan surat pendek.',
                'Membaca hafalan dengan tartil.',
                'Menjaga hafalan secara rutin.',
            ],
        ];

        foreach ($subjects as $subject) {

            $teacher = $teachers->random();

            $exampleList = collect($examples[$subject->name] ?? [])
                ->map(fn ($item) => '- ' . $item)
                ->implode("\n");

            $baseDescription = ($descriptions[$subject->name] ?? 'Materi ini berisi pembelajaran dasar yang sesuai dengan mata pelajaran.')
                . "\n\nContoh dalam Kehidupan Sehari-hari:\n"
                . $exampleList;

            // Materi PDF
            Material::create([
                'subject_id' => $subject->id,
                'user_id' => $teacher->id,
                'title' => 'Mengenal ' . $subject->name,
                'description' => $baseDescription . "\n\nPetunjuk Belajar\nSilakan baca dan pelajari modul {$subject->name} yang telah diunggah oleh guru dengan baik. Setelah selesai membaca, kerjakan tugas yang diberikan sesuai petunjuk!",
                'file_path' => 'materials/materi.pdf',
                'youtube_link' => null,
                'is_task' => false,
            ]);

            // Materi Youtube
            Material::create([
                'subject_id' => $subject->id,
                'user_id' => $teachers->random()->id,
                'title' => 'Video Pembelajaran ' . $subject->name,
                'description' => $baseDescription . "\n\nPetunjuk Belajar\nSilakan pelajari video pembelajaran {$subject->name} yang telah diunggah oleh guru dengan baik. Setelah selesai dipelajari, kerjakan tugas yang diberikan sesuai petunjuk!",
                'file_path' => null,
                'youtube_link' => $youtubeLink,
                'is_task' => false,
            ]);

            // Tugas
            Material::create([
                'subject_id' => $subject->id,
                'user_id' => $teachers->random()->id,
                'title' => 'Tugas ' . $subject->name,
                'description' => "Kerjakan tugas {$subject->name} sesuai materi yang telah dipelajari. Bacalah petunjuk dengan teliti, kemudian kumpulkan jawaban sesuai arahan guru.",
                'file_path' => 'materials/tugas.jpg',
                'youtube_link' => null,
                'is_task' => true,
            ]);
        }
    }
}