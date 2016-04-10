
<div class="form-group">
    {!! Form::label('name', 'Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control', 'readonly']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Description') !!}
    {!! Form::text('description', null, ['class' => 'form-control', 'readonly']) !!}
</div>

<div class="form-group">
    {!! Form::label('value', 'Description') !!}
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
</div>

<hr/>
<br>

<div class="form-group">
    {!! Form::submit($submitButton, ['class' => 'btn btn-primary form-control']) !!}
</div>
