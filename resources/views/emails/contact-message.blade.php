<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message - Anagata Executive</title>
</head>
<body style="background-color: #f5f5f5; padding: 40px 20px;">
    <div style="background-color: rgba(255, 255, 255, 0.9); padding: 40px 20px; position: relative;">
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('{{ url(asset('assets/hero-sec.png')) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; pointer-events: none;"></div>
        <div style="text-align: center; margin-bottom: 30px; position: relative; z-index: 1;">
        <img src="{{ asset('assets/hero-sec.png') }}" alt="Anagata Executive Logo"
     style="max-width: 150px; height: auto; display: block; margin: 0 auto;">

        </div>
        
        <div style="max-width: 600px; margin: 0 auto; background-color: rgba(255, 255, 255, 0.95); padding: 30px; border-radius: 8px; position: relative; z-index: 1;">
            <p style="white-space: pre-line; line-height: 1.8;">Halo Anagata Executive, I'm {{ $data['name'] }}, {{ $data['email'] }}{{ !empty($data['phone']) ? ', ' . $data['phone'] : '' }}

{{ $data['message'] }}</p>
        </div>
        
        <div style="text-align: center; margin-top: 30px; color: #666; font-size: 12px; position: relative; z-index: 1;">
            <p>This message was sent through the Anagata Executive contact form.</p>
        </div>
    </div>
</body>
</html>
