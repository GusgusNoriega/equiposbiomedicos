@props([
    'name',
    'class' => 'h-5 w-5',
])

@switch($name)
    @case('devices')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <rect x="3.75" y="5.75" width="10.5" height="12.5" rx="2.5" stroke="currentColor" stroke-width="1.6" />
            <path d="M8 3.75V5.75M10 3.75V5.75M8 18.25V20.25M10 18.25V20.25M14.25 9.5H18.25C19.6307 9.5 20.75 10.6193 20.75 12V15.5C20.75 16.8807 19.6307 18 18.25 18H14.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            <path d="M7.25 9.5H10.75M7.25 13H10.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
        </svg>
        @break

    @case('maintenance')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M14.5 4.25C16.5711 4.25 18.25 5.92893 18.25 8C18.25 8.89688 17.9352 9.72022 17.4093 10.3655L10.364 17.4109C9.71874 17.9367 8.89539 18.2515 7.99852 18.2515C5.92745 18.2515 4.24852 16.5726 4.24852 14.5015C4.24852 13.6046 4.56336 12.7813 5.08918 12.136L12.1345 5.0907C12.7798 4.56485 13.6031 4.25 14.5 4.25Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M13.25 7.75L16.25 10.75M7.75 20.25L5.75 18.25" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
        </svg>
        @break

    @case('shield')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 3.75L18.75 6.25V11.9783C18.75 16.2778 15.761 20.1347 12 21.25C8.23902 20.1347 5.25 16.2778 5.25 11.9783V6.25L12 3.75Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M9.25 12.25L11.1 14.1L14.9 10.3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        @break

    @case('settings')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M9.08684 4.50049C9.40949 3.34394 10.4627 2.5 11.75 2.5H12.25C13.5373 2.5 14.5905 3.34394 14.9132 4.50049L15.0601 5.02695C15.2462 5.6946 15.8848 6.12816 16.5673 6.08772L17.1057 6.05581C18.289 5.98574 19.3711 6.70557 19.8128 7.80553L20.036 8.36172C20.5213 9.57078 20.1357 10.9565 19.0891 11.7381L18.6466 12.0687C18.0931 12.4824 17.9069 13.225 18.1971 13.8478L18.4292 14.3463C18.9408 15.4446 18.6787 16.7552 17.7816 17.5628L17.3729 17.9307C16.4757 18.7383 15.1523 18.8617 14.1268 18.2476L13.6613 17.9692C13.0895 17.627 12.3691 17.627 11.7973 17.9692L11.3318 18.2476C10.3063 18.8617 8.98293 18.7383 8.08576 17.9307L7.67711 17.5628C6.77994 16.7552 6.51777 15.4446 7.02944 14.3463L7.26159 13.8478C7.55174 13.225 7.36557 12.4824 6.81206 12.0687L6.36953 11.7381C5.32289 10.9565 4.93735 9.57078 5.42263 8.36172L5.64586 7.80553C6.08758 6.70557 7.16967 5.98574 8.35293 6.05581L8.89134 6.08772C9.57388 6.12816 10.2124 5.6946 10.3986 5.02695L10.5455 4.50049" stroke="currentColor" stroke-width="1.45" />
            <circle cx="12" cy="12" r="2.75" stroke="currentColor" stroke-width="1.6" />
        </svg>
        @break

    @case('users')
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M12 13.25C14.3472 13.25 16.25 11.3472 16.25 9C16.25 6.65279 14.3472 4.75 12 4.75C9.65279 4.75 7.75 6.65279 7.75 9C7.75 11.3472 9.65279 13.25 12 13.25Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M5.75 18.5C5.75 16.1528 8.54822 14.25 12 14.25C15.4518 14.25 18.25 16.1528 18.25 18.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            <path d="M18.5 8C19.8807 8 21 9.11929 21 10.5C21 11.8807 19.8807 13 18.5 13M18.5 14.75C20.1893 14.75 21.5586 15.6816 21.5586 16.8301" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            <path d="M5.5 8C4.11929 8 3 9.11929 3 10.5C3 11.8807 4.11929 13 5.5 13M5.5 14.75C3.81067 14.75 2.44141 15.6816 2.44141 16.8301" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
        </svg>
        @break

    @case('overview')
    @default
        <svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M4.75 7.75C4.75 6.09315 6.09315 4.75 7.75 4.75H9.75C11.4069 4.75 12.75 6.09315 12.75 7.75V9.75C12.75 11.4069 11.4069 12.75 9.75 12.75H7.75C6.09315 12.75 4.75 11.4069 4.75 9.75V7.75Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M4.75 16.25C4.75 14.5931 6.09315 13.25 7.75 13.25H9.75C11.4069 13.25 12.75 14.5931 12.75 16.25V16.25C12.75 17.9069 11.4069 19.25 9.75 19.25H7.75C6.09315 19.25 4.75 17.9069 4.75 16.25V16.25Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M13.75 14.25C13.75 12.5931 15.0931 11.25 16.75 11.25H18.25C19.0784 11.25 19.75 11.9216 19.75 12.75V17.75C19.75 18.5784 19.0784 19.25 18.25 19.25H16.75C15.0931 19.25 13.75 17.9069 13.75 16.25V14.25Z" stroke="currentColor" stroke-width="1.6" />
            <path d="M13.75 6.25C13.75 5.42157 14.4216 4.75 15.25 4.75H18.25C19.0784 4.75 19.75 5.42157 19.75 6.25V7.75C19.75 9.40685 18.4069 10.75 16.75 10.75H15.25C14.4216 10.75 13.75 10.0784 13.75 9.25V6.25Z" stroke="currentColor" stroke-width="1.6" />
        </svg>
@endswitch
