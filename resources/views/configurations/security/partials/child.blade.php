<tr>
    <td rowspan="{{ count($child->children) + 1 }}">{{ $child->name }}</td>
</tr>
@foreach ($child->children as $grandchild)
    @include('configurations.security.partials.grandchild', ['grandchild' => $grandchild])
@endforeach
