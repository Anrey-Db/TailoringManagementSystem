<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            $table->json('metrics')->nullable()->after('label');
        });

        // Helper to upsert metric per (category,label)
        $set = function(string $category, string $label, array $metrics) {
            DB::table('sizes')
                ->where('category', $category)
                ->where('label', $label)
                ->update(['metrics' => json_encode($metrics), 'updated_at' => now()]);
        };

        // Shirt / Blouse
        $shirt = [
            'XS' => ['chest' => '81–84','shoulder' => '36','sleeve' => '21','length' => '60','waist' => '70–72'],
            'S'  => ['chest' => '85–88','shoulder' => '38','sleeve' => '22','length' => '62','waist' => '73–76'],
            'M'  => ['chest' => '89–93','shoulder' => '40','sleeve' => '23','length' => '64','waist' => '77–81'],
            'L'  => ['chest' => '94–98','shoulder' => '42','sleeve' => '24','length' => '66','waist' => '82–86'],
            'XL' => ['chest' => '99–104','shoulder' => '44','sleeve' => '25','length' => '68','waist' => '87–91'],
            '2XL'=> ['chest' => '105–110','shoulder' => '46','sleeve' => '26','length' => '70','waist' => '92–97'],
        ];
        foreach ($shirt as $label => $m) { $set('Shirt', $label, $m); }

        // Uniform (Top & Bottom Combined) — include top_length and bottom_length
        $uniform = [
            'XS' => ['chest'=>'81–84','waist'=>'64–68','hip'=>'86–90','bottom_length'=>'53/90','top_length'=>'56'],
            'S'  => ['chest'=>'85–88','waist'=>'69–73','hip'=>'91–95','bottom_length'=>'55/92','top_length'=>'58'],
            'M'  => ['chest'=>'89–93','waist'=>'74–78','hip'=>'96–100','bottom_length'=>'57/94','top_length'=>'60'],
            'L'  => ['chest'=>'94–98','waist'=>'79–83','hip'=>'101–105','bottom_length'=>'59/96','top_length'=>'62'],
            'XL' => ['chest'=>'99–104','waist'=>'84–88','hip'=>'106–110','bottom_length'=>'61/98','top_length'=>'64'],
            '2XL'=> ['chest'=>'105–110','waist'=>'89–94','hip'=>'111–115','bottom_length'=>'63/100','top_length'=>'66'],
        ];
        foreach ($uniform as $label => $m) { $set('Uniform', $label, $m); }

        // PE Uniform (Shirt & Shorts/Jogging Pants)
        $pe = [
            'XS' => ['chest'=>'84–88','waist'=>'66–70','hip'=>'88–92','shirt_length'=>'64','bottom_length'=>'45/95'],
            'S'  => ['chest'=>'89–93','waist'=>'71–75','hip'=>'93–97','shirt_length'=>'66','bottom_length'=>'47/97'],
            'M'  => ['chest'=>'94–98','waist'=>'76–80','hip'=>'98–102','shirt_length'=>'68','bottom_length'=>'49/99'],
            'L'  => ['chest'=>'99–104','waist'=>'81–85','hip'=>'103–107','shirt_length'=>'70','bottom_length'=>'51/101'],
            'XL' => ['chest'=>'105–110','waist'=>'86–90','hip'=>'108–112','shirt_length'=>'72','bottom_length'=>'53/103'],
            '2XL'=> ['chest'=>'111–116','waist'=>'91–96','hip'=>'113–118','shirt_length'=>'74','bottom_length'=>'55/105'],
        ];
        foreach ($pe as $label => $m) { $set('PE Uniform', $label, $m); }

        // Jersey (Top & Short)
        $jersey = [
            'XS' => ['chest'=>'84–88','waist'=>'66–70','hip'=>'88–92','jersey_length'=>'68','short_length'=>'45'],
            'S'  => ['chest'=>'89–93','waist'=>'71–75','hip'=>'93–97','jersey_length'=>'70','short_length'=>'47'],
            'M'  => ['chest'=>'94–98','waist'=>'76–80','hip'=>'98–102','jersey_length'=>'72','short_length'=>'49'],
            'L'  => ['chest'=>'99–104','waist'=>'81–85','hip'=>'103–107','jersey_length'=>'74','short_length'=>'51'],
            'XL' => ['chest'=>'105–110','waist'=>'86–90','hip'=>'108–112','jersey_length'=>'76','short_length'=>'53'],
            '2XL'=> ['chest'=>'111–116','waist'=>'91–96','hip'=>'113–118','jersey_length'=>'78','short_length'=>'55'],
        ];
        foreach ($jersey as $label => $m) { $set('Jersey', $label, $m); }

        // Coat / Blazer
        $coat = [
            'XS' => ['chest'=>'86–90','shoulder'=>'40','sleeve'=>'56','waist'=>'74–78','coat_length'=>'65'],
            'S'  => ['chest'=>'91–95','shoulder'=>'42','sleeve'=>'57','waist'=>'79–83','coat_length'=>'67'],
            'M'  => ['chest'=>'96–100','shoulder'=>'44','sleeve'=>'58','waist'=>'84–88','coat_length'=>'69'],
            'L'  => ['chest'=>'101–105','shoulder'=>'46','sleeve'=>'59','waist'=>'89–93','coat_length'=>'71'],
            'XL' => ['chest'=>'106–110','shoulder'=>'48','sleeve'=>'60','waist'=>'94–98','coat_length'=>'73'],
            '2XL'=> ['chest'=>'111–116','shoulder'=>'50','sleeve'=>'61','waist'=>'99–103','coat_length'=>'75'],
        ];
        foreach ($coat as $label => $m) { $set('Coat', $label, $m); }
    }

    public function down(): void
    {
        Schema::table('sizes', function (Blueprint $table) {
            if (Schema::hasColumn('sizes', 'metrics')) {
                $table->dropColumn('metrics');
            }
        });
    }
};
