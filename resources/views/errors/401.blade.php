@extends('layouts.app')
@section('content')
<div class="container">

	<div class="row">

		<div class="span12">

			<div class="error-container">
				<h1>401</h1>

				<h2>Unauthorized.</h2>

				<div class="error-details">
          <h2>{{ $exception->getMessage() }}</h2>
					Sorry, an error has occured! Why not try going back to the <a href="{{ route('home') }}">home page</a> or perhaps try following!

				</div> <!-- /error-details -->

				<div class="error-actions">
					<a href="{{ route('home') }}" class="btn btn-large btn-primary">
						<i class="icon-chevron-left"></i>
						&nbsp;
						Back to Home
					</a>



				</div> <!-- /error-actions -->

			</div> <!-- /error-container -->

		</div> <!-- /span12 -->

	</div> <!-- /row -->

</div> <!-- /container -->
@endsection
