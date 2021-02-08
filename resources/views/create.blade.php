@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        Create Todo
    </div>

    <div class="card-body">
        <form action="{{route('todo.store')}}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Add Todo </label>
                <input type="text" name="title" placeholder="Add Todo" class="form-control">
            </div>


            <div class="form-group">
                <button class="btn btn-success" type="submit">Add Todo</button>
            </div>
        </form>
    </div>
</div>


@endsection