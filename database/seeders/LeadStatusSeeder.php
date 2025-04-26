<?php

namespace Database\Seeders;

use App\Models\LeadStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeadStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        // blue = #0303fc
        // yellow = #fcfc03
        // green = #03fc03
        // red = #fc0303
        // gray = #808080
        // orange = #fc7f03
        // teal = #03fcfc
        $statuses = [
            [
                'name' => 'New',
                'slug' => 'new',
                'group' => 'intake',
                'color' => '#0303fc',
                'is_active' => true,
                'sort_order' => 1,
                'description' => 'Newly created lead.',
            ],
            [
                'name' => 'Unqualified',
                'slug' => 'unqualified',
                'group' => 'Intake',
                'color' => '#0303fc',
                'is_active' => true,
                'sort_order' => 2,
                'description' => 'Leads belum memenuhi syarat dasar seperti lokasi, usia, atau profesi',
            ],
            [
                'name' => 'Duplicate',
                'slug' => 'duplicate',
                'group' => 'Intake',
                'color' => '#0303fc',
                'is_active' => true,
                'sort_order' => 3,
                'description' => 'Data leads terduplikasi dari entri sebelumnya',
            ],
            [
                'name' => 'Invalid Contact',
                'slug' => 'invalid_contact',
                'group' => 'Intake',
                'color' => '#0303fc',
                'is_active' => true,
                'sort_order' => 4,
                'description' => 'Kontak tidak valid atau tidak bisa dihubungi',
            ],
            [
                'name' => 'Assigned',
                'slug' => 'assigned',
                'group' => 'Outreach',
                'color' => '#fcfc03',
                'is_active' => true,
                'sort_order' => 5,
                'description' => 'Sudah ditugaskan ke telesales',
            ],
            [
                'name' => 'Attempting Contact',
                'slug' => 'attempting_contact',
                'group' => 'Outreach',
                'color' => '#fcfc03',
                'is_active' => true,
                'sort_order' => 6,
                'description' => 'Sedang dihubungi melalui telepon, WA, atau email',
            ],
            [
                'name' => 'Contacted - No Answer',
                'slug' => 'contacted_no_answer',
                'group' => 'Outreach',
                'color' => '#fcfc03',
                'is_active' => true,
                'sort_order' => 7,
                'description' => 'Sudah dihubungi tapi tidak menjawab',
            ],
            [
                'name' => 'Contacted - Wrong Time',
                'slug' => 'contacted_wrong_time',
                'group' => 'Outreach',
                'color' => '#fcfc03',
                'is_active' => true,
                'sort_order' => 8,
                'description' => 'Leads tidak bisa bicara saat itu, minta dihubungi lagi',
            ],
            [
                'name' => 'Contacted - Callback',
                'slug' => 'contacted_callback',
                'group' => 'Outreach',
                'color' => '#fcfc03',
                'is_active' => true,
                'sort_order' => 9,
                'description' => 'Leads minta dihubungi ulang di waktu spesifik',
            ],
            [
                'name' => 'Qualified - Interested',
                'slug' => 'qualified_interested',
                'group' => 'Engagement',
                'color' => '#fc7f03',
                'is_active' => true,
                'sort_order' => 10,
                'description' => 'Leads menunjukkan minat dan memenuhi kriteria dasar',
            ],
            [
                'name' => 'In Discussion',
                'slug' => 'in_discussion',
                'group' => 'Engagement',
                'color' => '#fc7f03',
                'is_active' => true,
                'sort_order' => 11,
                'description' => 'Leads sudah tanya-tanya produk/jasa, masih proses diskusi',
            ],
            [
                'name' => 'Follow-Up Scheduled',
                'slug' => 'follow_up_scheduled',
                'group' => 'Engagement',
                'color' => '#fc7f03',
                'is_active' => true,
                'sort_order' => 12,
                'description' => 'Sudah dijadwalkan untuk di-follow-up',
            ],
            [
                'name' => 'Referral Contributor',
                'slug' => 'referral_contributor',
                'group' => 'Engagement',
                'color' => '#03fcfc',
                'is_active' => true,
                'sort_order' => 13,
                'description' => 'Leads belum menjadi klien namun telah memberikan referensi calon klien lain',
            ],
            [
                'name' => 'Need More Info',
                'slug' => 'need_more_info',
                'group' => 'Engagement',
                'color' => '#fc7f03',
                'is_active' => true,
                'sort_order' => 14,
                'description' => 'Leads minta informasi tambahan sebelum mengambil keputusan',
            ],
            [
                'name' => 'Under Nurturing',
                'slug' => 'under_nurturing',
                'group' => 'Nurturing',
                'color' => '#808080',
                'is_active' => true,
                'sort_order' => 15,
                'description' => 'Leads masuk proses nurturing seperti email series atau WA blast',
            ],
            [
                'name' => 'Cold Lead',
                'slug' => 'cold_lead',
                'group' => 'Nurturing',
                'color' => '#808080',
                'is_active' => true,
                'sort_order' => 16,
                'description' => 'Sudah lama tidak merespons, namun masih disimpan',
            ],
            [
                'name' => 'Long-Term Follow-Up',
                'slug' => 'long_term_follow_up',
                'group' => 'Nurturing',
                'color' => '#808080',
                'is_active' => true,
                'sort_order' => 17,
                'description' => 'Potensial closing jangka panjang, bukan prioritas saat ini',
            ],
            [
                'name' => 'Not Interested',
                'slug' => 'not_interested',
                'group' => 'Disqualified',
                'color' => '#fc0303',
                'is_active' => true,
                'sort_order' => 18,
                'description' => 'Leads secara eksplisit tidak tertarik',
            ],
            [
                'name' => 'No Budget',
                'slug' => 'no_budget',
                'group' => 'Disqualified',
                'color' => '#fc0303',
                'is_active' => true,
                'sort_order' => 19,
                'description' => 'Tertarik tapi tidak ada dana',
            ],
            [
                'name' => 'Using Competitor',
                'slug' => 'using_competitor',
                'group' => 'Disqualified',
                'color' => '#fc0303',
                'is_active' => true,
                'sort_order' => 20,
                'description' => 'Sudah menggunakan layanan kompetitor',
            ],
            [
                'name' => 'Blacklisted',
                'slug' => 'blacklisted',
                'group' => 'Disqualified',
                'color' => '#fc0303',
                'is_active' => true,
                'sort_order' => 21,
                'description' => 'Tidak sopan, spam, atau minta tidak dihubungi lagi',
            ],
            [
                'name' => 'Closed - Lost',
                'slug' => 'closed_lost',
                'group' => 'Disqualified',
                'color' => '#fc0303',
                'is_active' => true,
                'sort_order' => 22,
                'description' => 'Sudah diproses tapi tidak berhasil closing',
            ],
            [
                'name' => 'Closed - Won',
                'slug' => 'closed_won',
                'group' => 'Converted',
                'color' => '#03fc03',
                'is_active' => true,
                'sort_order' => 23,
                'description' => 'Berhasil closing, jadi klien',
            ],
            [
                'name' => 'Upsell Opportunity',
                'slug' => 'upsell_opportunity',
                'group' => 'Converted',
                'color' => '#03fc03',
                'is_active' => true,
                'sort_order' => 24,
                'description' => 'Sudah menjadi klien dan ada potensi penjualan lanjutan',
            ],
            [
                'name' => 'Refer#fc0303 Others',
                'slug' => 'refer#fc0303_others',
                'group' => 'Converted',
                'color' => '#03fc03',
                'is_active' => true,
                'sort_order' => 25,
                'description' => 'Memberikan referensi klien baru setelah menjadi klien',
            ],
        ];

        foreach ($statuses as $status) {
            LeadStatus::create($status);
        }
    }
}
