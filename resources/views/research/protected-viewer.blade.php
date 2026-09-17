<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $research->title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        html, body {
            margin: 0;
            padding: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            overflow-x: hidden;
            user-select: none;
            -webkit-user-select: none;
        }

        .viewer {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px 12px 60px;
        }

        .page {
            margin: 0 auto 18px;
            background: #fff;
            box-shadow: 0 4px 18px rgba(0,0,0,0.12);
            border-radius: 8px;
            overflow: hidden;
        }

        .page img {
            width: 100%;
            display: block;
            pointer-events: none;
            -webkit-user-drag: none;
        }

        iframe {
            width: 100%;
            height: 100vh;
            border: none;
            display: block;
        }

        @media print {
            body {
                display: none !important;
            }
        }
    </style>
</head>
<body>

@if (!empty($pageUrls) && count($pageUrls))
    <div class="viewer">
        @foreach ($pageUrls as $url)
            <div class="page">
                <img src="{{ $url }}" alt="Document page {{ $loop->iteration }}" draggable="false">
            </div>
        @endforeach
    </div>
@else
    <iframe
        src="{{ $signedUrl }}#toolbar=0&navpanes=0&scrollbar=0&zoom=page-fit"
        title="PDF Viewer">
    </iframe>
@endif

<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    document.addEventListener('dragstart', function(e) {
        e.preventDefault();
    });

    document.addEventListener('keydown', function(e) {
        const key = e.key.toLowerCase();

        if ((e.ctrlKey || e.metaKey) && ['p', 's', 'u'].includes(key)) {
            e.preventDefault();
            return false;
        }
    });

    window.onbeforeprint = function() {
        document.body.style.display = 'none';
    };

    window.onafterprint = function() {
        document.body.style.display = 'block';
    };
</script>

</body>
</html>