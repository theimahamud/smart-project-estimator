import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Index({ estimates }) {
    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        My Estimates
                    </h2>
                    <Link
                        href={route('estimates.create')}
                        className="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                    >
                        New Estimate
                    </Link>
                </div>
            }
        >
            <Head title="Estimates" />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6">
                            {estimates.data.length === 0 ? (
                                <div className="text-center py-12">
                                    <h3 className="text-lg font-medium text-gray-900 mb-2">
                                        No estimates yet
                                    </h3>
                                    <p className="text-gray-600 mb-4">
                                        Create your first project estimate to get started
                                    </p>
                                    <Link
                                        href={route('estimates.create')}
                                        className="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                                    >
                                        Create Estimate
                                    </Link>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    {estimates.data.map((estimate) => (
                                        <Link
                                            key={estimate.id}
                                            href={route('estimates.show', estimate.id)}
                                            className="block p-6 border rounded-lg hover:bg-gray-50 transition"
                                        >
                                            <div className="flex justify-between items-start">
                                                <div className="flex-1">
                                                    <h3 className="text-lg font-semibold text-gray-900 mb-2">
                                                        {estimate.project_name}
                                                    </h3>
                                                    <div className="flex gap-4 text-sm text-gray-600">
                                                        <span>
                                                            💰 ${estimate.total_cost?.toLocaleString() || 'N/A'}
                                                        </span>
                                                        <span>
                                                            ⏱️ {estimate.estimated_hours || 'N/A'} hours
                                                        </span>
                                                        <span>
                                                            📅 {estimate.estimated_days || 'N/A'} days
                                                        </span>
                                                        <span>
                                                            🎯 {estimate.complexity_level || 'N/A'}
                                                        </span>
                                                    </div>
                                                </div>
                                                <span
                                                    className={`px-3 py-1 rounded-full text-xs font-medium ${
                                                        estimate.status === 'completed'
                                                            ? 'bg-green-100 text-green-800'
                                                            : estimate.status === 'processing'
                                                            ? 'bg-yellow-100 text-yellow-800'
                                                            : 'bg-red-100 text-red-800'
                                                    }`}
                                                >
                                                    {estimate.status}
                                                </span>
                                            </div>
                                        </Link>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

