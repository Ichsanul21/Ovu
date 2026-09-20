<?php

// Katalog gejala Ovu ala Flo. 70+ item dalam 11 kategori.
// input: level (1-5) | check | select | number | text
// target: kolom daily_logs, atau json (masuk symptoms JSON).
// teen: false berarti disembunyikan di mode remaja.

return [
    'darah' => [
        'label' => 'Darah',
        'items' => [
            ['key' => 'bleeding', 'label' => 'Volume darah', 'input' => 'select', 'options' => [0 => 'Tidak ada', 1 => 'Bercak', 2 => 'Sedikit', 3 => 'Sedang', 4 => 'Deras'], 'target' => 'col:bleeding', 'teen' => true],
            ['key' => 'spotting', 'label' => 'Flek di luar haid', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'clots', 'label' => 'Gumpalan darah', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'nyeri' => [
        'label' => 'Nyeri',
        'items' => [
            ['key' => 'cramp', 'label' => 'Kram perut', 'input' => 'level', 'target' => 'col:cramp', 'teen' => true],
            ['key' => 'headache', 'label' => 'Sakit kepala', 'input' => 'level', 'target' => 'col:headache', 'teen' => true],
            ['key' => 'breast_pain', 'label' => 'Nyeri payudara', 'input' => 'level', 'target' => 'col:breast_pain', 'teen' => true],
            ['key' => 'back_pain', 'label' => 'Nyeri punggung', 'input' => 'level', 'target' => 'json', 'teen' => true],
            ['key' => 'lower_abdomen', 'label' => 'Nyeri perut bawah', 'input' => 'level', 'target' => 'json', 'teen' => true],
            ['key' => 'waist_pain', 'label' => 'Nyeri pinggang', 'input' => 'level', 'target' => 'json', 'teen' => true],
            ['key' => 'migraine', 'label' => 'Migrain', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'ovulation_pain', 'label' => 'Nyeri ovulasi (satu sisi)', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'joint_pain', 'label' => 'Nyeri sendi', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'leg_cramps', 'label' => 'Kram kaki', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'pencernaan' => [
        'label' => 'Pencernaan',
        'items' => [
            ['key' => 'nausea', 'label' => 'Mual', 'input' => 'level', 'target' => 'col:nausea', 'teen' => true],
            ['key' => 'bloating', 'label' => 'Kembung', 'input' => 'level', 'target' => 'json', 'teen' => true],
            ['key' => 'constipation', 'label' => 'Sembelit', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'diarrhea', 'label' => 'Diare', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'heartburn', 'label' => 'Mulas', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'craving', 'label' => 'Ngidam makanan', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'appetite_down', 'label' => 'Nafsu makan turun', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'appetite_up', 'label' => 'Nafsu makan naik', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'cairan' => [
        'label' => 'Cairan',
        'items' => [
            ['key' => 'cervical_fluid', 'label' => 'Lendir serviks', 'input' => 'select', 'options' => ['kering' => 'Kering', 'lengket' => 'Lengket', 'creamy' => 'Creamy', 'putih_telur' => 'Jernih licin'], 'target' => 'col:cervical_fluid', 'teen' => false],
            ['key' => 'discharge', 'label' => 'Keputihan banyak', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'itchy', 'label' => 'Gatal area intim', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'odor', 'label' => 'Bau tidak biasa', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'mood' => [
        'label' => 'Mood',
        'items' => [
            ['key' => 'mood', 'label' => 'Mood utama', 'input' => 'select', 'options' => ['senang' => 'Senang', 'tenang' => 'Tenang', 'sensitif' => 'Sensitif', 'cemas' => 'Cemas', 'sedih' => 'Sedih', 'marah' => 'Marah', 'lelahan' => 'Lelah hati'], 'target' => 'col:mood', 'teen' => true],
            ['key' => 'tearful', 'label' => 'Mudah menangis', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'irritable', 'label' => 'Mudah tersinggung', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'unfocused', 'label' => 'Sulit fokus', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'unmotivated', 'label' => 'Tidak bersemangat', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'calm_happy', 'label' => 'Ceria dan percaya diri', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'energi' => [
        'label' => 'Energi',
        'items' => [
            ['key' => 'energy', 'label' => 'Level energi', 'input' => 'level', 'target' => 'col:energy', 'teen' => true],
            ['key' => 'weak', 'label' => 'Lemas', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'sleepy', 'label' => 'Mengantuk', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'insomnia', 'label' => 'Sulit tidur', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'fresh', 'label' => 'Segar', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'active', 'label' => 'Aktif bergerak', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'dizzy', 'label' => 'Pusing', 'input' => 'level', 'target' => 'json', 'teen' => true],
        ],
    ],
    'tidur' => [
        'label' => 'Tidur',
        'items' => [
            ['key' => 'sleep_hours', 'label' => 'Jam tidur', 'input' => 'number', 'target' => 'col:sleep_hours', 'teen' => true],
            ['key' => 'sleep_quality', 'label' => 'Kualitas tidur', 'input' => 'select', 'options' => ['nyenyak' => 'Nyenyak', 'terbangun' => 'Sering terbangun', 'gelisah' => 'Gelisah'], 'target' => 'json', 'teen' => true],
            ['key' => 'dreams', 'label' => 'Mimpi vivid', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'late_night', 'label' => 'Begadang', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'stres' => [
        'label' => 'Stres',
        'items' => [
            ['key' => 'stress', 'label' => 'Level stres', 'input' => 'level', 'target' => 'col:stress', 'teen' => true],
            ['key' => 'overwhelmed', 'label' => 'Kewalahan', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'relaxed', 'label' => 'Santai', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
    'tes' => [
        'label' => 'Tes',
        'items' => [
            ['key' => 'bbt', 'label' => 'Suhu basal (C)', 'input' => 'number', 'target' => 'col:bbt', 'teen' => false],
            ['key' => 'lh_test', 'label' => 'Tes ovulasi LH', 'input' => 'select', 'options' => ['tidak_tes' => 'Tidak tes', 'negatif' => 'Negatif', 'positif' => 'Positif'], 'target' => 'col:lh_test', 'teen' => false],
            ['key' => 'testpack', 'label' => 'Testpack', 'input' => 'select', 'options' => ['tidak_tes' => 'Tidak tes', 'negatif' => 'Negatif', 'samar' => 'Samar', 'positif' => 'Positif'], 'target' => 'col:testpack', 'teen' => false],
        ],
    ],
    'intim' => [
        'label' => 'Intim',
        'teen_safe' => false,
        'items' => [
            ['key' => 'intercourse', 'label' => 'Hubungan intim', 'input' => 'check', 'target' => 'col:intercourse', 'teen' => false],
            ['key' => 'protected', 'label' => 'Dengan proteksi', 'input' => 'check', 'target' => 'col:protected', 'teen' => false],
            ['key' => 'libido_up', 'label' => 'Gairah naik', 'input' => 'check', 'target' => 'json', 'teen' => false],
            ['key' => 'libido_down', 'label' => 'Gairah turun', 'input' => 'check', 'target' => 'json', 'teen' => false],
            ['key' => 'pill_taken', 'label' => 'Pil KB diminum', 'input' => 'check', 'target' => 'col:pill_taken', 'teen' => false],
        ],
    ],
    'fisik' => [
        'label' => 'Fisik lain',
        'items' => [
            ['key' => 'acne', 'label' => 'Jerawat', 'input' => 'level', 'target' => 'col:acne', 'teen' => true],
            ['key' => 'fever', 'label' => 'Demam', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'cold', 'label' => 'Flu', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'cough', 'label' => 'Batuk', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'sore_throat', 'label' => 'Sakit tenggorokan', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'hair_loss', 'label' => 'Rambut rontok', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'dry_skin', 'label' => 'Kulit kering', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'weight_kg', 'label' => 'Berat badan (kg)', 'input' => 'number', 'target' => 'col:weight_kg', 'teen' => true],
            ['key' => 'exercise', 'label' => 'Olahraga ringan', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'exercise_hard', 'label' => 'Olahraga berat', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'alcohol', 'label' => 'Alkohol', 'input' => 'check', 'target' => 'json', 'teen' => false],
            ['key' => 'coffee', 'label' => 'Kopi berlebih', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'smoke', 'label' => 'Rokok', 'input' => 'check', 'target' => 'json', 'teen' => false],
            ['key' => 'vitamin', 'label' => 'Minum vitamin', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'painkiller', 'label' => 'Minum pereda nyeri', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'sweating', 'label' => 'Keringat berlebih', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'pale', 'label' => 'Wajah pucat', 'input' => 'check', 'target' => 'json', 'teen' => true],
            ['key' => 'heartbeat', 'label' => 'Jantung berdebar', 'input' => 'check', 'target' => 'json', 'teen' => true],
        ],
    ],
];
