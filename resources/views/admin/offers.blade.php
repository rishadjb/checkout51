<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

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
                        {{ 'Offer ID' }} <?php echo $data['sort'] == 'name' && $data['order'] == 'asc' ? "<b>ASC</b>" : "<a href='checkout51?sort=offer_id&order=asc'>ASC</a>" ?>/<?php echo $data['sort'] == 'name' && $data['order'] == 'desc' ? "<b>DESC</b>" : "<a href='checkout51?sort=offer_id&order=desc'>DESC</a>" ?>
                    </td>
                    <td class="key">Image</td>
                    <td class="key">Product</td>
                    <td class="key">Cashback</td>                    
                    <td class="key">
                        {{ 'Status' }} <?php echo $data['sort'] == 'cash_back' && $data['order'] == 'asc' ? "<b>ASC</b>" : "<a href='checkout51?sort=status&order=asc'>ASC</a>" ?>/<?php echo $data['sort'] == 'cash_back' && $data['order'] == 'desc' ? "<b>DESC</b>" : "<a href='checkout51?sort=status&order=desc'>DESC</a>" ?>
                    </td>
                 </tr>    
            </thead>
            <tbody>
                @foreach ($data['offers'] as $offer)               
                    <tr>
                        <td class="detail">
                            {{ $offer['offer_id'] }}
                        </td>
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
                            <a href="/offer/{{$offer['offer_id']}}/update?active={{ $offer['active'] ? 'false' : 'true' }}">{{ $offer['active'] ? 'Deactivate' : 'Activate' }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </body>
</html>
