<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="{{Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix('templates/base.css')}}">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .header, .footer {
                background: #76b844;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
            }
        </style>
    </head>
    <body>

        @include('header')

        <table class="offer-table">
            <thead>
                 <tr>
                    <td class="key">
                        {{ 'Image' }}
                    </td>
                    <td class="key">
                        {{ 'Product' }} <?php echo $data['sort'] == 'name' && $data['order'] == 'asc' ? "<b>ASC</b>" : "<a href='checkout51?sort=name&order=asc'>ASC</a>" ?>/<?php echo $data['sort'] == 'name' && $data['order'] == 'desc' ? "<b>DESC</b>" : "<a href='checkout51?sort=name&order=desc'>DESC</a>" ?>
                    </td>
                    <td class="key">
                        {{ 'Cashback' }} <?php echo $data['sort'] == 'cash_back' && $data['order'] == 'asc' ? "<b>ASC</b>" : "<a href='checkout51?sort=cash_back&order=asc'>ASC</a>" ?>/<?php echo $data['sort'] == 'cash_back' && $data['order'] == 'desc' ? "<b>DESC</b>" : "<a href='checkout51?sort=cash_back&order=desc'>DESC</a>" ?>
                    </td>
                    <td class="key">
                        {{ 'Buy Link' }}
                    </td>
                 </tr>    
            </thead>
            <tbody>
                @foreach ($data['offers'] as $offer)               
                    <tr>
                        <td class="detail">
                            <img src="{{ $offer['image_url'] }}" />
                        </td>
                        <td class="detail">
                            {{ $offer['product_name'] }}
                        </td>
                        <td class="detail">
                            ${{ $offer['cash_back'] }}
                        </td>
                        <td class="detail">
                            <a href="/offer/{{ $offer['offer_id'] }}">${{ $offer['cash_back'] }} Cash Back</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>
</html>
