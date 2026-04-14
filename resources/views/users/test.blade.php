@extends('layout.app')
@section('title')
    Тест
@endsection
@section('content')


<style>
    .answer-option {
        position: relative;
        display: flex;
        align-items: center;
        padding: 1rem 1.25rem;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 24px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        margin-bottom: 10px;
    }
    .answer-option:hover {
        border-color: #2f32bc;
        background: linear-gradient(135deg, rgba(225, 226, 254, 0.05) 0%, rgba(53, 56, 150, 0.05) 100%);
        transform: translateX(8px);
        box-shadow: 0 4px 12px rgba(47, 50, 188, 0.15);
    }
    .answer-option:active {
        transform: translateX(4px) scale(0.99);
    }
    .answer-option input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    .custom-checkbox {
        width: 24px;
        height: 24px;
        min-width: 24px;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        background: white;
        margin-right: 1rem;
        position: relative;
        transition: all 0.2s ease;
        display: inline-block;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox {
        background: linear-gradient(135deg, #2f32bc 0%, #353896 100%);
        border-color: #2f32bc;
        animation: checkboxPop 0.3s ease;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox::after {
        content: "✓";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: bold;
        animation: checkmarkFade 0.2s ease;
    }
    .answer-option input[type="checkbox"]:checked + .custom-checkbox::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.5);
        transform: translate(-50%, -50%);
        animation: ripple 0.4s ease-out;
    }
    .answer-text {
        flex: 1;
        font-size: 1rem;
        color: #1e293b;
        font-weight: 500;
        transition: all 0.2s ease;
        line-height: 1.4;
    }
    .answer-option input[type="checkbox"]:checked ~ .answer-text {
        color: #2f32bc;
        font-weight: 600;
    }
    .answer-badge {
        margin-left: 0.75rem;
        padding: 0.25rem 0.75rem;
        background: #f1f5f9;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s ease;
    }
    .answer-option input[type="checkbox"]:checked ~ .answer-badge {
        background: linear-gradient(135deg, #E1E2FE 0%, #353896 100%);
        color: white;
    }
    @keyframes checkboxPop {
        0% {
            transform: scale(0.9);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }
    @keyframes ripple {
        0% {
            width: 0;
            height: 0;
            opacity: 0.5;
        }
        100% {
            width: 40px;
            height: 40px;
            opacity: 0;
        }
    }
    @keyframes checkmarkFade {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.5);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }
</style>
@endsection
