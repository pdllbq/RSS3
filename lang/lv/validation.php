<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Laukam :attribute ir jābūt apstiprinātam.',
    'accepted_if' => 'Laukam :attribute ir jābūt apstiprinātam, ja :other ir :value.',
    'active_url' => 'Laukam :attribute ir jābūt derīgai URL adresei.',
    'after' => 'Laukam :attribute ir jābūt datumam pēc :date.',
    'after_or_equal' => 'Laukam :attribute ir jābūt datumam pēc vai vienādam ar :date.',
    'alpha' => 'Laukā :attribute drīkst būt tikai burti.',
    'alpha_dash' => 'Laukā :attribute drīkst būt tikai burti, cipari, defises un pasvītras.',
    'alpha_num' => 'Laukā :attribute drīkst būt tikai burti un cipari.',
    'any_of' => 'Lauks :attribute nav derīgs.',
    'array' => 'Laukam :attribute ir jābūt masīvam.',
    'ascii' => 'Laukā :attribute drīkst būt tikai vienbaita burti, cipari un simboli.',
    'before' => 'Laukam :attribute ir jābūt datumam pirms :date.',
    'before_or_equal' => 'Laukam :attribute ir jābūt datumam pirms vai vienādam ar :date.',
    'between' => [
        'array' => 'Laukā :attribute jābūt no :min līdz :max elementiem.',
        'file' => 'Laukam :attribute jābūt no :min līdz :max kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt no :min līdz :max.',
        'string' => 'Laukā :attribute jābūt no :min līdz :max rakstzīmēm.',
    ],
    'boolean' => 'Laukam :attribute ir jābūt patiess vai aplams.',
    'can' => 'Laukā :attribute ir neatļauta vērtība.',
    'confirmed' => 'Lauka :attribute apstiprinājums nesakrīt.',
    'contains' => 'Laukā :attribute trūkst obligātās vērtības.',
    'current_password' => 'Parole nav pareiza.',
    'date' => 'Laukam :attribute ir jābūt derīgam datumam.',
    'date_equals' => 'Laukam :attribute ir jābūt datumam, kas vienāds ar :date.',
    'date_format' => 'Laukam :attribute jāatbilst formātam :format.',
    'decimal' => 'Laukam :attribute jābūt ar :decimal decimālzīmēm.',
    'declined' => 'Laukam :attribute ir jābūt noraidītam.',
    'declined_if' => 'Laukam :attribute ir jābūt noraidītam, ja :other ir :value.',
    'different' => 'Laukiem :attribute un :other jābūt atšķirīgiem.',
    'digits' => 'Laukam :attribute jābūt :digits cipariem.',
    'digits_between' => 'Laukam :attribute jābūt no :min līdz :max cipariem.',
    'dimensions' => 'Laukam :attribute ir nederīgi attēla izmēri.',
    'distinct' => 'Laukā :attribute ir dublēta vērtība.',
    'doesnt_contain' => 'Lauks :attribute nedrīkst saturēt nevienu no šīm vērtībām: :values.',
    'doesnt_end_with' => 'Lauks :attribute nedrīkst beigties ar kādu no šīm vērtībām: :values.',
    'doesnt_start_with' => 'Lauks :attribute nedrīkst sākties ar kādu no šīm vērtībām: :values.',
    'email' => 'Laukam :attribute ir jābūt derīgai e-pasta adresei.',
    'encoding' => 'Laukam :attribute jābūt kodētam :encoding.',
    'ends_with' => 'Laukam :attribute jābeidzas ar kādu no šīm vērtībām: :values.',
    'enum' => 'Izvēlētais :attribute nav derīgs.',
    'exists' => 'Izvēlētais :attribute nav derīgs.',
    'extensions' => 'Laukam :attribute jābūt ar kādu no šiem paplašinājumiem: :values.',
    'file' => 'Laukam :attribute ir jābūt failam.',
    'filled' => 'Laukam :attribute ir jābūt aizpildītam.',
    'gt' => [
        'array' => 'Laukā :attribute jābūt vairāk nekā :value elementiem.',
        'file' => 'Laukam :attribute jābūt lielākam par :value kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt lielākam par :value.',
        'string' => 'Laukam :attribute jābūt garākam par :value rakstzīmēm.',
    ],
    'gte' => [
        'array' => 'Laukā :attribute jābūt vismaz :value elementiem.',
        'file' => 'Laukam :attribute jābūt lielākam vai vienādam ar :value kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt lielākam vai vienādam ar :value.',
        'string' => 'Laukam :attribute jābūt vismaz :value rakstzīmēm.',
    ],
    'hex_color' => 'Laukam :attribute ir jābūt derīgai heksadecimālai krāsai.',
    'image' => 'Laukam :attribute ir jābūt attēlam.',
    'in' => 'Izvēlētais :attribute nav derīgs.',
    'in_array' => 'Laukam :attribute jāpastāv laukā :other.',
    'in_array_keys' => 'Laukam :attribute jāsatur vismaz viena no šīm atslēgām: :values.',
    'integer' => 'Laukam :attribute ir jābūt veselam skaitlim.',
    'ip' => 'Laukam :attribute ir jābūt derīgai IP adresei.',
    'ipv4' => 'Laukam :attribute ir jābūt derīgai IPv4 adresei.',
    'ipv6' => 'Laukam :attribute ir jābūt derīgai IPv6 adresei.',
    'json' => 'Laukam :attribute ir jābūt derīgai JSON virknei.',
    'list' => 'Laukam :attribute ir jābūt sarakstam.',
    'lowercase' => 'Laukam :attribute jābūt ar mazajiem burtiem.',
    'lt' => [
        'array' => 'Laukā :attribute jābūt mazāk nekā :value elementiem.',
        'file' => 'Laukam :attribute jābūt mazākam par :value kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt mazākam par :value.',
        'string' => 'Laukam :attribute jābūt īsākam par :value rakstzīmēm.',
    ],
    'lte' => [
        'array' => 'Laukā :attribute nedrīkst būt vairāk par :value elementiem.',
        'file' => 'Laukam :attribute jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt mazākam vai vienādam ar :value.',
        'string' => 'Laukam :attribute jābūt ne garākam par :value rakstzīmēm.',
    ],
    'mac_address' => 'Laukam :attribute ir jābūt derīgai MAC adresei.',
    'max' => [
        'array' => 'Laukā :attribute nedrīkst būt vairāk par :max elementiem.',
        'file' => 'Lauks :attribute nedrīkst būt lielāks par :max kilobaitiem.',
        'numeric' => 'Lauks :attribute nedrīkst būt lielāks par :max.',
        'string' => 'Lauks :attribute nedrīkst būt garāks par :max rakstzīmēm.',
    ],
    'max_digits' => 'Laukā :attribute nedrīkst būt vairāk par :max cipariem.',
    'mimes' => 'Laukam :attribute jābūt failam ar tipu: :values.',
    'mimetypes' => 'Laukam :attribute jābūt failam ar tipu: :values.',
    'min' => [
        'array' => 'Laukā :attribute jābūt vismaz :min elementiem.',
        'file' => 'Laukam :attribute jābūt vismaz :min kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt vismaz :min.',
        'string' => 'Laukam :attribute jābūt vismaz :min rakstzīmēm.',
    ],
    'min_digits' => 'Laukā :attribute jābūt vismaz :min cipariem.',
    'missing' => 'Laukam :attribute jābūt neesošam.',
    'missing_if' => 'Laukam :attribute jābūt neesošam, ja :other ir :value.',
    'missing_unless' => 'Laukam :attribute jābūt neesošam, ja vien :other nav :value.',
    'missing_with' => 'Laukam :attribute jābūt neesošam, ja ir norādīts :values.',
    'missing_with_all' => 'Laukam :attribute jābūt neesošam, ja ir norādīti :values.',
    'multiple_of' => 'Laukam :attribute jābūt skaitļa :value reizinājumam.',
    'not_in' => 'Izvēlētais :attribute nav derīgs.',
    'not_regex' => 'Lauka :attribute formāts nav derīgs.',
    'numeric' => 'Laukam :attribute ir jābūt skaitlim.',
    'password' => [
        'letters' => 'Laukā :attribute jābūt vismaz vienam burtam.',
        'mixed' => 'Laukā :attribute jābūt vismaz vienam lielajam un vienam mazajam burtam.',
        'numbers' => 'Laukā :attribute jābūt vismaz vienam ciparam.',
        'symbols' => 'Laukā :attribute jābūt vismaz vienam simbolam.',
        'uncompromised' => 'Norādītais :attribute ir parādījies datu noplūdē. Lūdzu, izvēlieties citu :attribute.',
    ],
    'present' => 'Laukam :attribute ir jābūt norādītam.',
    'present_if' => 'Laukam :attribute ir jābūt norādītam, ja :other ir :value.',
    'present_unless' => 'Laukam :attribute ir jābūt norādītam, ja vien :other nav :value.',
    'present_with' => 'Laukam :attribute ir jābūt norādītam, ja ir norādīts :values.',
    'present_with_all' => 'Laukam :attribute ir jābūt norādītam, ja ir norādīti :values.',
    'prohibited' => 'Lauks :attribute ir aizliegts.',
    'prohibited_if' => 'Lauks :attribute ir aizliegts, ja :other ir :value.',
    'prohibited_if_accepted' => 'Lauks :attribute ir aizliegts, ja :other ir apstiprināts.',
    'prohibited_if_declined' => 'Lauks :attribute ir aizliegts, ja :other ir noraidīts.',
    'prohibited_unless' => 'Lauks :attribute ir aizliegts, ja vien :other nav iekļauts :values.',
    'prohibits' => 'Lauks :attribute aizliedz lauka :other klātbūtni.',
    'regex' => 'Lauka :attribute formāts nav derīgs.',
    'required' => 'Lauks :attribute ir obligāts.',
    'required_array_keys' => 'Laukam :attribute jāsatur ieraksti: :values.',
    'required_if' => 'Lauks :attribute ir obligāts, ja :other ir :value.',
    'required_if_accepted' => 'Lauks :attribute ir obligāts, ja :other ir apstiprināts.',
    'required_if_declined' => 'Lauks :attribute ir obligāts, ja :other ir noraidīts.',
    'required_unless' => 'Lauks :attribute ir obligāts, ja vien :other nav iekļauts :values.',
    'required_with' => 'Lauks :attribute ir obligāts, ja ir norādīts :values.',
    'required_with_all' => 'Lauks :attribute ir obligāts, ja ir norādīti :values.',
    'required_without' => 'Lauks :attribute ir obligāts, ja nav norādīts :values.',
    'required_without_all' => 'Lauks :attribute ir obligāts, ja nav norādīta neviena no šīm vērtībām: :values.',
    'same' => 'Laukam :attribute jāsakrīt ar :other.',
    'size' => [
        'array' => 'Laukā :attribute jābūt :size elementiem.',
        'file' => 'Laukam :attribute jābūt :size kilobaitiem.',
        'numeric' => 'Laukam :attribute jābūt :size.',
        'string' => 'Laukam :attribute jābūt :size rakstzīmēm.',
    ],
    'starts_with' => 'Laukam :attribute jāsākas ar kādu no šīm vērtībām: :values.',
    'string' => 'Laukam :attribute ir jābūt teksta virknei.',
    'timezone' => 'Laukam :attribute ir jābūt derīgai laika joslai.',
    'unique' => ':attribute jau ir aizņemts.',
    'uploaded' => 'Neizdevās augšupielādēt :attribute.',
    'uppercase' => 'Laukam :attribute jābūt ar lielajiem burtiem.',
    'url' => 'Laukam :attribute ir jābūt derīgai URL adresei.',
    'ulid' => 'Laukam :attribute ir jābūt derīgam ULID.',
    'uuid' => 'Laukam :attribute ir jābūt derīgam UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
