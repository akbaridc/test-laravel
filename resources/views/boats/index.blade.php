@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Boats</h2>
            </div>
            <div class="pull-right">
                @can('boat-create')
                    <a class="btn btn-success" href="{{ route('boats.create') }}"> Create New Boat</a>
                @endcan
            </div>
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <table class="table table-bordered">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Details</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($boats as $boat)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $boat->name }}</td>
                <td>{{ $boat->detail }}</td>
                <td>
                    <form action="{{ route('boats.destroy', $boat->id) }}" method="POST">
                        <a class="btn btn-info" href="{{ route('boats.show', $boat->id) }}">Show</a>
                        @can('boat-edit')
                            <a class="btn btn-primary" href="{{ route('boats.edit', $boat->id) }}">Edit</a>
                        @endcan
                        @csrf
                        @method('DELETE')
                        @can('boat-delete')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        @endcan
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
    {!! $boats->links() !!}
@endsection
