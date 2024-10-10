<tr>
    <td rowspan="{{ count($module->children) + 1 }}">{{ $module->name }}</td>
</tr>
@foreach ($module->children as $child)
    @include('configurations.security.partials.child', ['child' => $child])
@endforeach
