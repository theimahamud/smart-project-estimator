import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';

export default function Show({ estimate }) {
    const handleDelete = () => {
        if (confirm('Are you sure you want to delete this estimate?')) {
            router.delete(route('estimates.destroy', estimate.id));
        }
    };

    return (
        <AuthenticatedLayout
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800">
                        {estimate.project_name}
                    </h2>
                    <div className="flex gap-2">
                        <Link
                            href={route('estimates.index')}
                            className="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600"
                        >
                            Back
                        </Link>
                        <button
                            onClick={handleDelete}
                            className="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            }
        >
            <Head title={estimate.project_name} />

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="text-sm text-gray-600 mb-1">Total Cost</div>
                            <div className="text-2xl font-bold text-blue-600">
                                ${estimate.total_cost?.toLocaleString() || 'N/A'}
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="text-sm text-gray-600 mb-1">Hours</div>
                            <div className="text-2xl font-bold text-green-600">
                                {estimate.estimated_hours || 'N/A'}
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="text-sm text-gray-600 mb-1">Timeline</div>
                            <div className="text-2xl font-bold text-purple-600">
                                {estimate.estimated_days || 'N/A'} days
                            </div>
                        </div>
                        <div className="bg-white p-6 rounded-lg shadow">
                            <div className="text-sm text-gray-600 mb-1">Complexity</div>
                            <div className="text-2xl font-bold text-orange-600 capitalize">
                                {estimate.complexity_level || 'N/A'}
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-lg shadow">
                        <h3 className="text-lg font-semibold mb-4">Requirements</h3>
                        <p className="text-gray-700 whitespace-pre-wrap">{estimate.raw_requirements}</p>
                    </div>

                    {estimate.requirements && estimate.requirements.length > 0 && (
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h3 className="text-lg font-semibold mb-4">Feature Breakdown</h3>
                            <div className="space-y-3">
                                {estimate.requirements.map((req) => (
                                    <div key={req.id} className="border-l-4 border-blue-500 pl-4 py-2">
                                        <div className="flex justify-between items-start">
                                            <div>
                                                <h4 className="font-medium text-gray-900">{req.feature_name}</h4>
                                                {req.description && (
                                                    <p className="text-sm text-gray-600 mt-1">{req.description}</p>
                                                )}
                                            </div>
                                            <div className="text-right">
                                                <div className="text-sm font-medium text-gray-900">
                                                    {req.estimated_hours}h
                                                </div>
                                                <div className="text-xs text-gray-500 capitalize">
                                                    {req.complexity_level}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {estimate.team_composition && (
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h3 className="text-lg font-semibold mb-4">Team Composition</h3>
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {Object.entries(estimate.team_composition).map(([role, count]) => (
                                    <div key={role} className="border rounded-lg p-4">
                                        <div className="text-sm text-gray-600">{role}</div>
                                        <div className="text-xl font-bold text-gray-900">{count}</div>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    {estimate.breakdowns && estimate.breakdowns.length > 0 && (
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h3 className="text-lg font-semibold mb-4">Cost Breakdown</h3>
                            <div className="overflow-x-auto">
                                <table className="w-full">
                                    <thead className="bg-gray-50">
                                        <tr>
                                            <th className="px-4 py-2 text-left text-sm font-medium text-gray-700">
                                                Role
                                            </th>
                                            <th className="px-4 py-2 text-right text-sm font-medium text-gray-700">
                                                Hours
                                            </th>
                                            <th className="px-4 py-2 text-right text-sm font-medium text-gray-700">
                                                Rate/hr
                                            </th>
                                            <th className="px-4 py-2 text-right text-sm font-medium text-gray-700">
                                                Cost
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody className="divide-y divide-gray-200">
                                        {estimate.breakdowns.map((breakdown) => (
                                            <tr key={breakdown.id}>
                                                <td className="px-4 py-3 text-sm text-gray-900">
                                                    {breakdown.team_role.name}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-right text-gray-900">
                                                    {breakdown.hours}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-right text-gray-900">
                                                    ${breakdown.hourly_rate}
                                                </td>
                                                <td className="px-4 py-3 text-sm text-right font-medium text-gray-900">
                                                    ${breakdown.cost.toLocaleString()}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    )}

                    {estimate.region && (
                        <div className="bg-white p-6 rounded-lg shadow">
                            <h3 className="text-lg font-semibold mb-2">Region</h3>
                            <p className="text-gray-700">
                                {estimate.region.name} (Cost Multiplier: {estimate.region.cost_multiplier}x)
                            </p>
                        </div>
                    )}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}

