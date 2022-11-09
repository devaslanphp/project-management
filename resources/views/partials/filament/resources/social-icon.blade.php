<div class="inline-flex items-center space-x-2 rtl:space-x-reverse px-4">
    <div class="w-full flex items-center gap-2">
        @php($socials = $getState())
        @foreach($socials as $social)
            @switch($social->provider)
                @case('google')
                    <x-icon name="fab-google" class="social-icon google"/>
                    @break
                @case('facebook')
                    <x-icon name="fab-facebook" class="social-icon facebook"/>
                    @break
                @case('github')
                    <x-icon name="fab-github" class="social-icon github"/>
                    @break
                @case('twitter')
                    <x-icon name="fab-twitter" class="social-icon twitter"/>
                    @break
            @endswitch
        @endforeach
    </div>
</div>
