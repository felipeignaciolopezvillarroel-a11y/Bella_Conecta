@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge([
    'class' => 'w-full px-4 py-3 text-sm text-[#1F1B1A] bg-white border border-[#EEE3DD] rounded-xl shadow-sm 
                transition-all duration-200 outline-none 
                focus:border-pink-500 focus:ring-2 focus:ring-pink-200 
                placeholder:text-gray-400 disabled:bg-gray-100 disabled:text-gray-500 disabled:cursor-not-allowed'
]) }}>