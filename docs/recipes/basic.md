#Basic setup
Create one plan, create and assign one feature, create one public key for user ID `1` and domain `test.com`

    use Sribna\Licensor\Models\Plan;
    use Sribna\Licensor\Models\Feature;
    use Sribna\Licensor\Models\Key;
    use Illuminate\Support\Str;
    
    $plan = Plan::create(
        [
            'title' => 'Free',
            'text' => '14-days trial',
            'lifetime' => 60 * 60 * 24 * 14,
            'price' => 0,
            'status' => 1,
            'weight' => 0
        ]
    );
    
    $feature = Feature::create(
        [
            'id' => 'download_core',
            'title' => 'Download core',
            'text' => 'Download core files',
            'status' => true
        ]
    );

    $plan->features()->attach($feature);

    $key = Key::create(
        [
            'status' => true,
            'domain' => 'test.com',
            'plan_id' => $plan->id,
            'user_id' => 1,
            'id' => Str::random();
        ]
    );
    
    $publicKey = $key->id;

Also, make sure you have created at least one [secret](secrets.md) and your licensees use them in their checker files.
