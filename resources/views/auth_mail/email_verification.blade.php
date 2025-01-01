<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel Auth api</title>
</head>

<body>

    @php
        $url = config('frontend.url');
    @endphp
    <div class="container">
        <div class="row">
            <h1 class="app-name">Laravel Auth api</h1>
            <div class="email-password-info">
                <div class="heading-text">
                    <h1>Welcome to Laravel Auth api. Verify your account. </h1>
                </div>
                <div class="description">
                    <p>Hi <bold>{{ $userName }}! </bold>
                    <p>
                        Thanks for creating an account on Artofcse.
                    </p>
                    <p>
                        Your account has been created . Please click the following to activate your account
                    </p>

                    <a href="{{ env('APP_URL') }}/verify/{{ $token }}"> Click here to activate your
                        account</a>
                    <br />
                    <p>
                        We look forward to seeing you soon.
                    </p>
                </div>
            </div>
        </div>
    </div>


</body>

</html>
