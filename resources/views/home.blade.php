<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>{{ env('APP_NAME') }}</title>
  <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">

</head>

<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6 text-center">
        <table class="table">
          <thead>

            <tr>
              <th colspan="7">Lig Tablosu</th>
            </tr>

          </thead>
          <tbody>
            <tr>
              <th>Takımlar</th>
              <th>PTS</th>
              <th>P</th>
              <th>W</th>
              <th>D</th>
              <th>L</th>
              <th>GD</th>
            </tr>
            @foreach ($leagueList as $l)
              <tr>
                <td>{{ $l->name }}</td>
                <td>{{ $l->pts }}</td>
                <td>{{ $l->p }}</td>
                <td>{{ $l->w }}</td>
                <td>{{ $l->d }}</td>
                <td>{{ $l->l }}</td>
                <td>{{ $l->gd }}</td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            @if ($currentLeague->week < 6)
              <tr colspan="6">
                <td> <a href="{{ route('playAll') }}" class="btn btn-primary">Ligi Bitir</a> </td>
                <td> <a href="{{ route('play') }}" class="btn btn-success"> Sonraki Hafta</a> </td>
              </tr>
            @else
              <tr>
                <td> <a href="{{ route('resetLeague') }}" class="btn btn-warning"> Ligi Sıfırla</a> </td>
              </tr>
            @endif
          </tfoot>
        </table>
      </div>
      @if ($currentLeague->week)
        <div class="col-md-3 text-center">
          <table class="table">
            <thead>

              <tr>
                <th colspan="3">Lig Tablosu</th>
              </tr>

            </thead>
            <tbody>
              <tr>
                <th colspan="3"> {{ $currentLeague->week }}. Hafta Maç Sonuçları</th>
              </tr>
              @foreach ($currentWeekMatches as $m)
                <tr>
                  <td>{{ $m->hostTeam->name }}</td>
                  <td>{{ $m->result }}</td>
                  <td>{{ $m->awayTeam->name }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
      @if ($predictionsOfChampionships)
        <div class="col-md-3 text-center">
          <table class="table">
            <thead>
              <tr>
                @if ($currentLeague->week == 6)
                  <th colspan="3">Şampiyon Takım</th>
                @else
                  <th colspan="3"> {{ $currentLeague->week }}. Hafta Şampiyonluk İhtimalleri</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @if ($currentLeague->week == 6)
                  <tr>
                    <td>{{ $predictionsOfChampionships[0]['team']->name }}</td>
                  </tr>
              @else
                @foreach ($predictionsOfChampionships as $poc)
                  <tr>
                    <td>{{ $poc['team']->name }}</td>
                    <td>{{ $poc['rate'] }} %</td>
                  </tr>
                @endforeach

              @endif

            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
  <script src="{{ asset('js/jquery.js') }}"></script>
  <script src="{{ asset('js/bootstrap.js') }}"></script>

</body>

</html>
