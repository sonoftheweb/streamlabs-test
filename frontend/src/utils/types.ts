export type User = {
  id?: number
  name: string
  email: string
}

export const SUBSCRIPTION = {
  Tier1: 1,
  Tier2: 2,
  Tier3: 3
} as const

export type SubscriptionKeys = keyof typeof SUBSCRIPTION

export type Subscriber = {
  id: number
  name: string
  subscription_tier: SubscriptionKeys
  created_at: string
  updated_at: string
  subscription?: (typeof SUBSCRIPTION)[keyof typeof SUBSCRIPTION]
}

export type Donation = {
  id: number
  amount: number
  currency: string
  donation_message: string
  created_at: string
  updated_at: string
}

export type MerchSale = {
  id: number
  item: string
  count: number
  price: number
  created_at: string
  updated_at: string
}

export type Follower = {
  id: number
  name: string
  created_at: string
  updated_at: string
}

export type UserEvent = {
  id?: number
  summary?: string
  read: boolean
  related_data: Follower | MerchSale | Donation | Subscriber
  created_at: string
  updated_at: string
}
