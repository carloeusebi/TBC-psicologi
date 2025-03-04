import type { PageProps } from '@inertiajs/core';
import { Form } from 'laravel-precognition-vue-inertia/dist/types';
import type { LucideIcon } from 'lucide-vue-next';
import { type Component } from 'vue';

export interface Auth {
    user: User;
    canChangePassword: boolean;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    hidden?: boolean;
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: {
        location: string;
        url: string;
        port: null | number;
        defaults: Record<string, unknown>;
        routes: Record<string, string>;
    };
    flash: {
        success: string | null;
        error: string | null;
        info: string | null;
    };
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface Patient {
    id: string;
    first_name: string;
    last_name: string;
    name: string;
    gender: string | null;
    gender_label: string | null;
    birth_date: string | null;
    age: number | null;
    birth_place: string | null;
    address: string | null;
    codice_fiscale: string | null;
    therapy_start_date: string;
    email: string | null;
    phone: string | null;
    weight: number | null;
    height: number | null;
    education: string | null;
    job: string | null;
    cohabitants: string | null;
    drugs: string | null;
    archived_at: string | null;
    created_at: string;
    updated_at: string;

    evaluations_count?: number;
}

export type BreadcrumbItemType = BreadcrumbItem;

export type PriceInterval = 'month' | 'year';

export interface SubscriptionItem {
    id: number;
    subscription_id: number;
    stripe_id: string;
    stripe_product: string;
    stripe_price: string;
    quantity: number;
    created_at: string;
    updated_at: string;
}

export interface Subscription {
    id: string;
    user_id: number;
    type: string;
    stripe_id: string;
    stripe_price: string;
    stripe_status: string;
    quantity: number;
    trial_ends_at: string | null;
    ends_at: string | null;
    created_at: string;
    updated_at: string;
    items: SubscriptionItem[];
}

export interface Price {
    id: number;
    plan_id: number;
    stripe_id: string;
    interval: PriceInterval;
    amount: number;
    label: string;
}

export interface Plan {
    id: number;
    stripe_id: string;
    name: string;
    description: string;
    features: Array<string>;
    abilities: Record<string, number>;
    prices: Price[];
}

export interface Invoice extends Record<string, unknown> {
    id: string;
    created: string;
    total: number;
    currency: string;
    paid: boolean;
    status: string;
}

export type PrecognitionForm = Form<Record<string, unknown>>;

export interface TableAction {
    label: string;
    icon?: Component;
    onClick: VoidFunction;
}

export interface LaravelPaginatedResponse<T> {
    data: Array<T>;
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}
