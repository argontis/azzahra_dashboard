@extends('layouts.app')

@section('content')
<!-- Header Area -->
<div class="page-header">
    <div class="flex items-center gap-2 text-white">
        <i data-feather="zap" class="w-5 h-5"></i>
        <h1 class="text-xl font-bold tracking-wide">Quick Service</h1>
        <span class="hidden md:inline-block text-white text-xs opacity-90 ml-4 border-l border-white/20 pl-4">Manage quick service data</span>
    </div>
    
    <div class="flex items-center gap-3">
        <div class="relative hidden sm:block">
            <i data-feather="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input type="text" placeholder="Search..." class="pl-9 pr-4 py-2 rounded-md border-0 focus:ring-2 focus:ring-blue-400 text-gray-700 text-sm w-48 lg:w-80 shadow-sm">
        </div>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm relative">
            <i data-feather="bell" class="w-4 h-4"></i>
            <span class="absolute top-2 right-2 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
        </button>
        <button class="w-9 h-9 bg-white rounded-md flex items-center justify-center text-gray-600 hover:bg-gray-100 transition-colors shadow-sm">
            <i data-feather="mail" class="w-4 h-4"></i>
        </button>
    </div>
</div>

<div class="content-area">
    <div class="grid grid-cols-12 gap-6">
        <div class="col-span-12 xxl:col-span-9 grid grid-cols-12 gap-6">
            <!-- BEGIN: General Report -->
            <div class="col-span-12 mt-8">
                <div class="intro-y flex items-center h-10">
                    <h2 class="text-lg font-medium truncate mr-5">
                        General Report - Quick Service
                    </h2>
                    <a href="{{ route('quickservice.index') }}" class="ml-auto flex text-theme-1"> 
                        <i data-feather="refresh-ccw" class="w-4 h-4 mr-3"></i> Reload Data 
                    </a>
                </div>
                <div class="grid grid-cols-12 gap-6 mt-5">
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('quickservice.antrean', 'proses') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="zap" class="report-box__icon text-green-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-green-500 rounded-full mt-1 tooltip cursor-pointer" title="QS Diproses - {{ $stats['proses'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['proses'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">QS Diproses</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('quickservice.antrean', 'konfirmasi') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="phone-outgoing" class="report-box__icon text-yellow-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full mt-1 tooltip cursor-pointer" title="Konfirmasi - {{ $stats['konfirmasi'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['konfirmasi'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Konfirmasi QS</div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('quickservice.antrean', 'pelunasan') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="credit-card" class="report-box__icon text-red-500 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-red-500 rounded-full mt-1 tooltip cursor-pointer" title="Pelunasan - {{ $stats['pelunasan'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['pelunasan'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Pelunasan QS</div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-span-12 sm:col-span-6 xl:col-span-3 intro-y">
                        <div class="report-box zoom-in">
                            <a href="{{ route('quickservice.antrean', 'lunas') }}">
                                <div class="box p-5 bg-white rounded-md shadow-md border border-gray-200">
                                    <div class="flex">
                                        <i data-feather="check-circle" class="report-box__icon text-blue-700 w-8 h-8"></i> 
                                        <div class="ml-auto">
                                            <div class="w-3 h-3 bg-blue-700 rounded-full mt-1 tooltip cursor-pointer" title="Selesai (Lunas) - {{ $stats['lunas'] }}"></div>
                                        </div>
                                    </div>
                                    <div class="text-3xl font-bold leading-8 mt-6 text-gray-800">{{ $stats['lunas'] }}</div>
                                    <div class="text-base text-gray-600 mt-1">Selesai / Lunas</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END: General Report -->          
        </div>
    </div>
</div>
@endsection
