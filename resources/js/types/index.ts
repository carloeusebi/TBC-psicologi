import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';

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
        success: string;
        error: string;
        info: string;
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
