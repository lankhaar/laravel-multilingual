@if(count($locales = getMultilingualLocales()) > 1)
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true"
           aria-expanded="false">{{ __('Language') }}</a>
        <div class="dropdown-menu">
            @foreach($locales as $locale => $label)
                <a class="dropdown-item" href="{{ route('switch-locale', ['locale' => $locale]) }}">{{ __($label) }}</a>
            @endforeach
        </div>
    </li>
@endif
