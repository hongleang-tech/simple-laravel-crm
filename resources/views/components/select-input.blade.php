@props(['disabled' => false, 'options' => [], 'selected' => null])

<div class="w-full min-w-[200px]">
    <div class="relative">
        <select @disabled($disabled)
            {{ $attributes->merge(['class' => 'w-full bg-transparent text-slate-700 text-sm border border-slate-200 rounded pl-3 pr-8 py-3 min-h-[41px] transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md appearance-none cursor-pointer']) }}>
            @foreach ($options as $option)
                <option value="{{ $option['value'] }}" @selected(old($attributes->get('name'), $selected) === $option['value'])>
                    {{ $option['label'] }}
                </option>
            @endforeach
        </select>
    </div>
</div>
